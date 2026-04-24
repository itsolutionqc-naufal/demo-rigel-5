<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Http\Request;
use App\Models\SaleTransaction; // Using SaleTransaction for withdrawal requests
use App\Models\Setting;
use App\Services\CommissionWithdrawalService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of pending transactions for approval.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'topup'); // Default to topup
        $actor = $request->user();
        $availableCommission = null;
        
        // Get date filters (Y-m-d)
        $startDateInput = $request->get('start_date');
        $endDateInput = $request->get('end_date');
        $startDate = null;
        $endDate = null;
        try {
            if ($startDateInput) {
                $startDate = Carbon::createFromFormat('Y-m-d', $startDateInput)->startOfDay();
            }
            if ($endDateInput) {
                $endDate = Carbon::createFromFormat('Y-m-d', $endDateInput)->endOfDay();
            }
        } catch (\Throwable $e) {
            $startDate = null;
            $endDate = null;
        }
        
        // Build query
        $query = SaleTransaction::query()
            ->where('transaction_type', $type)
            ->with(['user' => function ($q) {
                $q->select('id', 'name', 'username', 'email', 'avatar', 'role');
            }]);

        if ($actor && $actor->isMarketing()) {
            $query->visibleToMarketing($actor);
        }
        
        // Apply date filters if provided (avoid whereDate() to keep indexes usable)
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        // Get transactions
        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());
        
        // Calculate total balance available (approved transactions)
        $totalBalanceQuery = SaleTransaction::where('status', 'success')
            ->where('transaction_type', $type);

        if ($actor && $actor->isMarketing()) {
            $totalBalanceQuery->visibleToMarketing($actor);
        }
            
        if ($startDate && $endDate) {
            $totalBalanceQuery->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $totalBalanceQuery->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $totalBalanceQuery->where('created_at', '<=', $endDate);
        }
        
        $totalBalance = $totalBalanceQuery->sum('amount');

        if ($actor && $actor->isMarketing() && $type === 'withdrawal') {
            $availableCommission = Commission::query()
                ->where('user_id', $actor->id)
                ->where('withdrawn', false)
                ->sum('amount');
        }

        return view('wallet.index', compact('transactions', 'type', 'totalBalance', 'availableCommission'));
    }

    public function requestMarketingWithdrawal(Request $request)
    {
        $actor = $request->user();
        if (! $actor || ! $actor->isMarketing()) {
            abort(403);
        }

        $validated = $request->validate([
            'account_name' => 'required|string',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'whatsapp_number' => 'nullable|string',
            'address' => 'nullable|string',
            'amount' => 'required|numeric|min:1',
        ]);

        $totalCommission = Commission::query()
            ->where('user_id', $actor->id)
            ->where('withdrawn', false)
            ->sum('amount');

        $withdrawAmount = (float) $validated['amount'];

        if ($withdrawAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah penarikan harus lebih dari 0',
            ], 400);
        }

        if ($withdrawAmount > (float) $totalCommission) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak mencukupi',
            ], 400);
        }

        $hasNonFinalWithdrawal = SaleTransaction::query()
            ->where('user_id', $actor->id)
            ->where('transaction_type', 'withdrawal')
            ->whereIn('status', ['pending', 'process'])
            ->exists();

        if ($hasNonFinalWithdrawal) {
            return response()->json([
                'success' => false,
                'message' => 'Masih ada penarikan yang sedang diproses. Silakan tunggu sampai selesai.',
            ], 400);
        }

        $transaction = DB::transaction(function () use ($actor, $validated, $withdrawAmount) {
            $transactionCode = 'WD-' . Str::upper(Str::random(10));

            $saleTransaction = SaleTransaction::create([
                'transaction_code' => $transactionCode,
                'user_id' => $actor->id,
                'amount' => $withdrawAmount,
                'status' => 'pending',
                'transaction_type' => 'withdrawal',
                'description' => "Penarikan ke {$validated['bank_name']} - {$validated['account_number']}",
                'payment_method' => $validated['bank_name'],
                'bank_name' => $validated['bank_name'],
                'account_number' => $validated['account_number'],
                'account_name' => $validated['account_name'],
                'whatsapp_number' => $validated['whatsapp_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'commission_rate' => 0,
                'commission_amount' => 0,
            ]);

            app(CommissionWithdrawalService::class)->reserveForWithdrawal($saleTransaction, $withdrawAmount);

            return $saleTransaction;
        });

        app(NotificationService::class)->notifyCommissionWithdrawal($actor, $withdrawAmount, 'pending');

        // Notify admin via Telegram payout bot (best-effort).
        if (! app()->environment('testing')) {
            try {
                $payoutBotId = (string) config('services.telegram.payout_bot_id', '');
                if (blank($payoutBotId)) {
                    logger()->warning('Telegram payout bot not configured for marketing withdrawal notification (TELEGRAM_PAYOUT_BOT_ID missing)', [
                        'transaction_code' => $transaction->transaction_code ?? null,
                    ]);
                } else {
                    $payoutTelegram = \App\Services\TelegramService::forBot($payoutBotId);

                    if (! $payoutTelegram) {
                        logger()->warning('Telegram payout bot not available for marketing withdrawal notification (inactive or missing token)', [
                            'payout_bot_id' => $payoutBotId,
                            'transaction_code' => $transaction->transaction_code ?? null,
                        ]);
                    } else {
                        $message = \App\Support\TelegramMessageFormatter::heading('PERMINTAAN PENARIKAN');
                        $message .= \App\Support\TelegramMessageFormatter::bullet('Kode', (string) ($transaction->transaction_code ?? '-'));
                        $message .= \App\Support\TelegramMessageFormatter::bullet('User', (string) ($actor->username ?: $actor->name ?: $actor->email ?: $actor->id));
                        $message .= \App\Support\TelegramMessageFormatter::bullet('Email', (string) ($actor->email ?? '-'));
                        $message .= "\n";
                        $message .= \App\Support\TelegramMessageFormatter::bullet('Nominal', 'Rp ' . number_format((float) $withdrawAmount, 0, ',', '.'), false);
                        $message .= \App\Support\TelegramMessageFormatter::bullet('Bank', (string) ($validated['bank_name'] ?? '-'));
                        $message .= \App\Support\TelegramMessageFormatter::bullet('No. Rek', (string) ($validated['account_number'] ?? '-'));
                        $message .= \App\Support\TelegramMessageFormatter::bullet('Atas Nama', (string) ($validated['account_name'] ?? '-'));
                        if (! empty($validated['whatsapp_number'])) {
                            $message .= \App\Support\TelegramMessageFormatter::bullet('WhatsApp', (string) $validated['whatsapp_number']);
                        }
                        if (! empty($validated['address'])) {
                            $message .= \App\Support\TelegramMessageFormatter::bullet('Alamat', (string) $validated['address'], false);
                        }
                        $message .= \App\Support\TelegramMessageFormatter::divider();
                        $message .= \App\Support\TelegramMessageFormatter::bullet('Status', 'Menunggu konfirmasi admin', false);

                        $txCode = (string) ($transaction->transaction_code ?? '');
                        $buttons = [
                            ['text' => '✅ APPROVE', 'callback_data' => "approve|{$payoutBotId}|{$txCode}"],
                            ['text' => '❌ REJECT', 'callback_data' => "reject|{$payoutBotId}|{$txCode}"],
                        ];

                        $payoutTelegram->sendMessageWithButtons($payoutTelegram->getChatId(), $message, $buttons);
                    }
                }
            } catch (\Throwable $e) {
                logger()->error('Telegram marketing withdrawal notification failed', [
                    'error' => $e->getMessage(),
                    'transaction_code' => $transaction->transaction_code ?? null,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Penarikan berhasil diajukan',
            'transaction_id' => $transaction->id,
            'amount' => $withdrawAmount,
            'remaining_balance' => Commission::query()
                ->where('user_id', $actor->id)
                ->where('withdrawn', false)
                ->sum('amount'),
        ]);
    }

    /**
     * Approve a transaction.
     */
    public function approve(Request $request, $id)
    {
        if ($request->user() && $request->user()->isMarketing()) {
            abort(403);
        }

        $transaction = SaleTransaction::findOrFail($id);
        $this->authorize('update', $transaction);

        // Update status to success (approved)
        $transaction->update(['status' => 'success']);

        return redirect()->back()->with('success', 'Transaksi berhasil disetujui!');
    }

    /**
     * Reject a transaction.
     */
    public function reject(Request $request, $id)
    {
        if ($request->user() && $request->user()->isMarketing()) {
            abort(403);
        }

        $transaction = SaleTransaction::findOrFail($id);
        $this->authorize('update', $transaction);

        // Check if already failed to avoid double refund
        if ($transaction->status === 'failed') {
            return redirect()->back()->with('error', 'Transaksi sudah ditolak sebelumnya.');
        }

        // Restore commissions if it's a withdrawal
        if ($transaction->transaction_type === 'withdrawal') {
            app(CommissionWithdrawalService::class)->restoreForWithdrawal($transaction);
        }

        // Update status to failed (rejected)
        $transaction->update(['status' => 'failed']);

        return redirect()->back()->with('success', 'Transaksi berhasil ditolak dan saldo dikembalikan!');
    }
}
