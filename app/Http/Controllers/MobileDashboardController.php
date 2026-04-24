<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Commission;
use App\Models\HostSubmission;
use App\Models\Notification;
use App\Models\SaleTransaction;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Services\CommissionWithdrawalService;
use App\Services\TelegramService;
use App\Support\TelegramMessageFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MobileDashboardController extends Controller
{
    public function index(Request $request, $page = 'dashboard')
    {
        // Get data for dashboard page
        if ($page === 'dashboard') {
            // Get published articles for mobile dashboard
            $articles = Article::where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('mobile.home', [
                'page' => $page,
                'articles' => $articles,
            ]);
        }

        // Get data for job page - use Service model
        if ($page === 'job') {
            $services = Service::where('status', 'active')
                ->where('is_active', true)
                ->where('category', 'reseller_coin')
                ->orderBy('name')
                ->get();

            return view('mobile.home', [
                'page' => $page,
                'services' => $services,
            ]);
        }

        // Get data for history page - use SaleTransaction
        if ($page === 'history') {
            $userId = Auth::id();

            // Get filter parameters
            $status = $request->get('status', 'all');
            $perPage = 10;

            // OPTIMIZED: Single query with conditional aggregation for statistics
            $stats = SaleTransaction::where('user_id', $userId)
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) as success,
                    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = "success" THEN commission_amount ELSE 0 END) as commission
                ')
                ->first();

            // Build query with eager loading to prevent N+1
            $query = SaleTransaction::with([
                'user' => function($q) { $q->select('id', 'name', 'email'); },
                'service' => function($q) { $q->select('id', 'name', 'image'); }
            ])
            ->select('id', 'user_id', 'service_name', 'amount', 'commission_amount', 
                     'status', 'transaction_type', 'created_at')
            ->where('user_id', $userId);

            // Apply status filter
            if ($status !== 'all') {
                $query->where('status', $status);
            }

            // Get paginated transactions
            $transactions = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);

            // Get statistics from optimized query
            $totalTransactions = $stats->total ?? 0;
            $successTransactions = $stats->success ?? 0;
            $pendingTransactions = $stats->pending ?? 0;
            $totalCommission = $stats->commission ?? 0;

            return view('mobile.home', [
                'page' => $page,
                'transactions' => $transactions,
                'totalTransactions' => $totalTransactions,
                'successTransactions' => $successTransactions,
                'pendingTransactions' => $pendingTransactions,
                'totalCommission' => $totalCommission,
                'currentStatus' => $status,
            ]);
        }

        // Get data for wallet page - get user's commission balance and transaction history
        if ($page === 'wallet') {
            $userId = Auth::id();

            // Get date filters from request
            $startDateInput = $request->get('start_date');
            $endDateInput = $request->get('end_date');
            $transactionType = $request->get('transaction_type', 'all'); // all, income, outcome
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

            // OPTIMIZED: Single query for all commission statistics
            $commissionStats = Commission::where('user_id', $userId)
                ->selectRaw('
                    SUM(amount) as total_earned,
                    SUM(CASE WHEN withdrawn = false THEN amount ELSE 0 END) as available,
                    SUM(CASE WHEN withdrawn = true THEN amount ELSE 0 END) as withdrawn
                ')
                ->first();

            $availableCommission = $commissionStats->available ?? 0;
            $totalCommissionEarned = $commissionStats->total_earned ?? 0;
            $withdrawnCommission = $commissionStats->withdrawn ?? 0;

            // OPTIMIZED: Use pagination and limit data loaded into memory
            // Build combined transaction history with proper pagination
            $commissionQuery = Commission::where('user_id', $userId)
                ->with(['saleTransaction' => function($q) {
                    $q->select('id', 'service_name', 'amount', 'status');
                    $q->with(['service' => function($sq) {
                        $sq->select('id', 'name', 'image');
                    }]);
                }])
                ->orderBy('created_at', 'desc');

            // Apply date filters
            if ($startDate && $endDate) {
                $commissionQuery->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($startDate) {
                $commissionQuery->where('created_at', '>=', $startDate);
            } elseif ($endDate) {
                $commissionQuery->where('created_at', '<=', $endDate);
            }

            $commissionHistory = $commissionQuery->limit(50)->get() // Limit to prevent memory issues
                ->map(function ($commission): array {
                    $saleTransaction = $commission->saleTransaction;
                    $serviceName = $saleTransaction?->service_name ?? 'Komisi Job';
                    $serviceImage = $saleTransaction?->service?->image;

                    return [
                        'id' => $commission->id,
                        'date' => $commission->created_at,
                        'type' => 'income',
                        'source' => $saleTransaction ? $serviceName : 'Komisi',
                        'service_image' => $serviceImage,
                        'amount' => (float) $commission->amount, // Show commission amount for balance calculation
                        'transaction_amount' => $saleTransaction ? (float) $saleTransaction->amount : null, // Keep transaction amount for display
                        'status' => 'success',
                        'description' => $saleTransaction ? "Komisi dari {$serviceName}" : 'Komisi',
                        'job_id' => $commission->sale_transaction_id,
                    ];
                });

            // OPTIMIZED: Add pagination for withdrawal history
            $withdrawalQuery = SaleTransaction::where('user_id', $userId)
                ->where('transaction_type', 'withdrawal')
                ->select('id', 'user_id', 'amount', 'status', 'description', 'bank_name', 
                         'account_number', 'account_name', 'created_at')
                ->orderBy('created_at', 'desc');

            if ($startDate && $endDate) {
                $withdrawalQuery->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($startDate) {
                $withdrawalQuery->where('created_at', '>=', $startDate);
            } elseif ($endDate) {
                $withdrawalQuery->where('created_at', '<=', $endDate);
            }

            $withdrawalHistory = $withdrawalQuery->limit(50)->get()
                ->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'date' => $transaction->created_at,
                        'type' => 'outcome',
                        'source' => 'Penarikan Dana',
                        'amount' => $transaction->amount,
                        'status' => $transaction->status,
                        'description' => $transaction->description ?? 'Penarikan ke '.$transaction->bank_name,
                        'bank_name' => $transaction->bank_name,
                        'account_number' => $transaction->account_number,
                        'account_name' => $transaction->account_name,
                    ];
                });

            // Merge and sort by date
            $allTransactions = $commissionHistory->concat($withdrawalHistory)
                ->sortByDesc('date');

            // Apply transaction type filter
            if ($transactionType !== 'all') {
                $allTransactions = $allTransactions->filter(function ($transaction) use ($transactionType) {
                    return $transaction['type'] === $transactionType;
                });
            }

            // Calculate running balance - start from current balance and work backwards
            // This ensures the most recent transaction shows the current balance
            $runningBalance = $availableCommission; // Start with current available balance

            $transactionsWithBalance = $allTransactions->map(function ($transaction) use (&$runningBalance) {
                // Store the balance AFTER this transaction
                $transaction['balance_after'] = $runningBalance;

                // Then adjust balance for the NEXT (older) transaction
                if ($transaction['type'] === 'income') {
                    // If this was income, previous balance was LESS
                    $runningBalance -= $transaction['amount'];
                } elseif ($transaction['type'] === 'outcome' && $transaction['status'] === 'success') {
                    // If this was successful withdrawal, previous balance was MORE
                    $runningBalance += $transaction['amount'];
                }

                return $transaction;
            })->values();

            // Paginate transactions - 5 per page
            $page = (int) $request->get('trans_page', 1);
            $perPage = 5;
            $total = $transactionsWithBalance->count();
            $transactions = $transactionsWithBalance->slice(($page - 1) * $perPage, $perPage)->values();
            $totalPages = ceil($total / $perPage);

            return view('mobile.home', [
                'page' => 'wallet',
                'balance' => $availableCommission,
                'totalCommissionEarned' => $totalCommissionEarned,
                'withdrawnCommission' => $withdrawnCommission,
                'transactions' => $transactions,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalTransactions' => $total,
            ]);
        }

        // Get data for submit-data page - get service by ID and payment methods
        if ($page === 'submit-data' && $request->has('service')) {
            $service = Service::find($request->service);

            // Get active payment methods
            // If service has specific payment methods, use those
            // Otherwise, use global payment methods (service_id is null)
            $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)
                ->where(function($query) use ($request) {
                    $query->where('service_id', $request->service)
                          ->orWhereNull('service_id');
                })
                ->orderBy('type')
                ->orderBy('name')
                ->get();

            return view('mobile.home', [
                'page' => $page,
                'service' => $service,
                'paymentMethods' => $paymentMethods,
            ]);
        }

        // Get data for news-detail page
        if ($page === 'news-detail' && $request->has('id')) {
            $article = Article::with(['user' => function($q) {
                    $q->select('id', 'name', 'email');
                }])
                ->where('status', 'published')
                ->select('id', 'title', 'content', 'excerpt', 'category', 'image', 'status', 'views', 'user_id', 'created_at', 'updated_at', 'published_at')
                ->find($request->id);

            if (! $article) {
                // Article not found
                return view('mobile.home', [
                    'page' => $page,
                    'article' => null,
                    'error' => 'Artikel tidak ditemukan',
                ]);
            }

            // Increment article views
            $article->increment('views');

            return view('mobile.home', [
                'page' => $page,
                'article' => $article,
            ]);
        }

        // Get data for notification page - get user's notifications
        if ($page === 'notification') {
            $notifications = Notification::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $unreadCount = Notification::where('user_id', Auth::id())
                ->whereNull('read_at')
                ->count();

            return view('mobile.home', [
                'page' => $page,
                'notifications' => $notifications,
                'unreadCount' => $unreadCount,
            ]);
        }

        // Get data for hunter page - app dropdown list
        if ($page === 'hunter') {
            $services = Service::where('status', 'active')
                ->where('is_active', true)
                ->where('category', 'talent_hunter')
                ->select('id', 'name', 'image')
                ->orderBy('name')
                ->get();

            return view('mobile.home', [
                'page' => $page,
                'services' => $services,
            ]);
        }

        // Get data for host-history page - host submissions history
        if ($page === 'host-history') {
            $userId = Auth::id();

            // Get filter parameters
            $status = $request->get('status', 'all');
            $perPage = 10;

            // Statistics
            $stats = SaleTransaction::where('user_id', $userId)
                ->where('transaction_type', 'host_submit')
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) as passed,
                    SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as not_passed
                ')
                ->first();

            $submissionsQuery = HostSubmission::query()
                ->with([
                    'service:id,name,image',
                    'saleTransaction:id,user_id,status,created_at,description,transaction_code,service_name',
                ])
                ->whereHas('saleTransaction', function ($q) use ($userId) {
                    $q->where('user_id', $userId)->where('transaction_type', 'host_submit');
                });

            if ($status !== 'all') {
                $submissionsQuery->whereHas('saleTransaction', function ($q) use ($userId, $status) {
                    $q->where('user_id', $userId)
                        ->where('transaction_type', 'host_submit')
                        ->where('status', $status);
                });
            }

            $submissions = $submissionsQuery->orderBy('created_at', 'desc')->paginate($perPage);

            $totalCommission = Commission::where('user_id', $userId)->sum('amount');

            return view('mobile.home', [
                'page' => $page,
                'submissions' => $submissions,
                'totalSubmissions' => $stats->total ?? 0,
                'passedSubmissions' => $stats->passed ?? 0,
                'notPassedSubmissions' => $stats->not_passed ?? 0,
                'totalCommission' => $totalCommission ?? 0,
                'currentStatus' => $status,
            ]);
        }

        // For other pages
        return view('mobile.home', ['page' => $page]);
    }

    public function submitHost(Request $request)
    {
        $validated = $request->validate([
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => [
                'integer',
                Rule::exists('services', 'id')
                    ->where('category', 'talent_hunter')
                    ->where('status', 'active')
                    ->where('is_active', true),
            ],
            'host_id' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'whatsapp_host' => 'required|string|max:30',
            'form_filled' => 'required|in:yes,no',
        ]);

        $description = $validated['form_filled'] === 'yes' ? 'Formulir: Sudah' : 'Formulir: Belum';

        $services = Service::query()
            ->whereIn('id', $validated['service_ids'])
            ->where('category', 'talent_hunter')
            ->where('status', 'active')
            ->where('is_active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $transactionIds = [];

        DB::transaction(function () use ($services, $validated, $description, &$transactionIds) {
            foreach ($services as $service) {
                $transactionCode = 'HUNTER-' . strtoupper(str_replace('.', '', uniqid('', true)));

                $transaction = SaleTransaction::create([
                    'transaction_code' => $transactionCode,
                    'user_id' => Auth::id(),
                    'status' => 'process', // Seleksi
                    'transaction_type' => 'host_submit',
                    'service_name' => $service->name,
                    'user_id_input' => $validated['host_id'],
                    'nickname' => $validated['nickname'],
                    'whatsapp_number' => $validated['whatsapp_host'],
                    'description' => $description,
                    'amount' => 0,
                    'commission_rate' => 0,
                    'commission_amount' => 0,
                ]);

                HostSubmission::create([
                    'sale_transaction_id' => $transaction->id,
                    'service_id' => $service->id,
                    'host_id' => $validated['host_id'],
                    'nickname' => $validated['nickname'],
                    'whatsapp_number' => $validated['whatsapp_host'],
                    'form_filled' => $validated['form_filled'] === 'yes',
                ]);

                $transactionIds[] = $transaction->id;
            }
        });

        // Send Telegram confirmation after response (avoid hanging request).
        if (!empty($transactionIds)) {
            app()->terminating(function () use ($transactionIds) {
                try {
                    $transactions = SaleTransaction::query()
                        ->whereIn('id', $transactionIds)
                        ->select('id', 'transaction_code', 'service_name', 'user_id_input', 'nickname', 'whatsapp_number', 'description')
                        ->get();

                    foreach ($transactions as $transaction) {
                        $hostSubmission = HostSubmission::query()
                            ->where('sale_transaction_id', $transaction->id)
                            ->with('service.telegramBot')
                            ->first();

                        $service = $hostSubmission?->service;
                        if (! $service) {
                            // Fallback for legacy records; avoid relying on non-unique names when possible.
                            $service = \App\Models\Service::query()
                                ->where('name', $transaction->service_name)
                                ->where('category', 'talent_hunter')
                                ->where('status', 'active')
                                ->where('is_active', true)
                                ->with('telegramBot')
                                ->first();
                        }

                        $tgService = \App\Services\TelegramService::forService($service);
                        $botKey = ($service && $service->telegramBot && $service->telegramBot->is_active)
                            ? (string) $service->telegramBot->id
                            : 'default';

                        $formulirValue = '';
                        if (!empty($transaction->description)) {
                            $desc = trim((string) $transaction->description);
                            if (str_starts_with($desc, 'Formulir:')) {
                                $formulirValue = trim(substr($desc, strlen('Formulir:')));
                            } else {
                                $formulirValue = $desc;
                            }
                        }

                        $message = TelegramMessageFormatter::heading('KONFIRMASI SUBMIT HOST');
                        $message .= TelegramMessageFormatter::bullet('Kode', (string) $transaction->transaction_code);
                        $message .= "\n";
                        $message .= TelegramMessageFormatter::bullet('Aplikasi', (string) $transaction->service_name);
                        $message .= TelegramMessageFormatter::bullet('ID Host', (string) $transaction->user_id_input);
                        $message .= TelegramMessageFormatter::bullet('Nickname', (string) $transaction->nickname);
                        $message .= TelegramMessageFormatter::bullet('WhatsApp Host', (string) $transaction->whatsapp_number);
                        if ($formulirValue !== '') {
                            $message .= TelegramMessageFormatter::bullet('Formulir', $formulirValue);
                        }
                        $message .= TelegramMessageFormatter::divider();
                        $message .= TelegramMessageFormatter::bullet('Status', 'Menunggu verifikasi agency');

                        $buttons = [
                            ['text' => '✅ LOLOS', 'callback_data' => "approve|{$botKey}|{$transaction->transaction_code}"],
                            ['text' => '❌ TIDAK LOLOS', 'callback_data' => "reject|{$botKey}|{$transaction->transaction_code}"],
                        ];

                        $tgResult = $tgService->sendMessageWithButtons(
                            $tgService->getChatId(),
                            $message,
                            $buttons
                        );

                        if (!empty($tgResult['success']) && isset($tgResult['message_id'])) {
                            cache()->set(
                                "tg_message_{$tgResult['message_id']}",
                                $transaction->transaction_code,
                                now()->addHours(24)
                            );
                        }
                    }
                } catch (\Throwable $e) {
                    Log::error('Telegram send failed (submitHost terminating)', [
                        'error' => $e->getMessage(),
                        'transaction_ids' => $transactionIds,
                    ]);
                }
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'talent yang anda submit masuk proses seleksi, hasil seleksi akan Di update secara berkala',
            'created_count' => count($transactionIds),
        ]);
    }

    public function uploadProof(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $destinationPath = public_path('uploads/images/job-user');

            // Create directory if it doesn't exist
            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);

            return response()->json([
                'success' => true,
                'file_path' => 'uploads/images/job-user/'.$filename,
                'filename' => $filename,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file uploaded',
        ], 400);
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'whatsapp_number' => 'nullable|string',
            'address' => 'nullable|string',
            'amount' => 'required|numeric|min:1', // Amount to withdraw
        ]);

        $actor = $request->user();
        $withdrawAmount = (float) $request->amount;

        if ($withdrawAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah penarikan harus lebih dari 0',
            ], 400);
        }

        $totalCommission = (float) Commission::query()
            ->where('user_id', Auth::id())
            ->where('withdrawn', false)
            ->sum('amount');

        if ($withdrawAmount > $totalCommission) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak mencukupi',
            ], 400);
        }

        if ($actor) {
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
        }

        try {
            $transaction = null;
            $remainingBalance = null;

            DB::transaction(function () use ($request, $withdrawAmount, &$transaction, &$remainingBalance) {
                $transactionCode = 'WD-' . Str::upper(Str::random(10));
                $transaction = SaleTransaction::create([
                    'transaction_code' => $transactionCode,
                    'user_id' => Auth::id(),
                    'amount' => $withdrawAmount,
                    'status' => 'pending',
                    'transaction_type' => 'withdrawal',
                    'description' => "Penarikan ke {$request->bank_name} - {$request->account_number}",
                    'payment_method' => $request->bank_name,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'account_name' => $request->account_name,
                    'whatsapp_number' => $request->whatsapp_number,
                    'address' => $request->address,
                    'commission_rate' => 0,
                    'commission_amount' => 0,
                ]);

                app(CommissionWithdrawalService::class)->reserveForWithdrawal($transaction, $withdrawAmount);

                $remainingBalance = (float) Commission::query()
                    ->where('user_id', Auth::id())
                    ->where('withdrawn', false)
                    ->sum('amount');
            });
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e instanceof \RuntimeException ? $e->getMessage() : 'Terjadi kesalahan saat mengajukan penarikan',
            ], 400);
        }

        // Notify admin via Telegram payout bot (best-effort).
        if (! app()->environment('testing')) {
            try {
                $payoutBotId = (string) config('services.telegram.payout_bot_id', '');
                if (filled($payoutBotId)) {
                    $payoutTelegram = TelegramService::forBot($payoutBotId);

                    if (! $payoutTelegram) {
                        Log::warning('Telegram payout bot not available for withdrawal notification (inactive or missing token)', [
                            'payout_bot_id' => $payoutBotId,
                            'transaction_code' => $transaction->transaction_code ?? null,
                        ]);
                    } else {
                        $actor = $request->user();

                        $message = TelegramMessageFormatter::heading('PERMINTAAN PENARIKAN');
                        $message .= TelegramMessageFormatter::bullet('Kode', (string) ($transaction->transaction_code ?? '-'));
                        $message .= TelegramMessageFormatter::bullet('User', (string) ($actor?->username ?: $actor?->name ?: $actor?->email ?: $actor?->id ?: '-'));
                        $message .= TelegramMessageFormatter::bullet('Email', (string) ($actor?->email ?? '-'));
                        $message .= "\n";
                        $message .= TelegramMessageFormatter::bullet('Nominal', 'Rp ' . number_format((float) $withdrawAmount, 0, ',', '.'), false);
                        $message .= TelegramMessageFormatter::bullet('Bank', (string) ($request->bank_name ?? '-'));
                        $message .= TelegramMessageFormatter::bullet('No. Rek', (string) ($request->account_number ?? '-'));
                        $message .= TelegramMessageFormatter::bullet('Atas Nama', (string) ($request->account_name ?? '-'));
                        if (! empty($request->whatsapp_number)) {
                            $message .= TelegramMessageFormatter::bullet('WhatsApp', (string) $request->whatsapp_number);
                        }
                        if (! empty($request->address)) {
                            $message .= TelegramMessageFormatter::bullet('Alamat', (string) $request->address, false);
                        }
                        $message .= TelegramMessageFormatter::divider();
                        $message .= TelegramMessageFormatter::bullet('Status', 'Menunggu konfirmasi admin', false);

                        $txCode = (string) ($transaction->transaction_code ?? '');
                        $buttons = [
                            ['text' => '✅ APPROVE', 'callback_data' => "approve|{$payoutBotId}|{$txCode}"],
                            ['text' => '❌ REJECT', 'callback_data' => "reject|{$payoutBotId}|{$txCode}"],
                        ];

                        $payoutTelegram->sendMessageWithButtons($payoutTelegram->getChatId(), $message, $buttons);
                    }
                } else {
                    Log::warning('Telegram payout bot not configured for withdrawal notification (TELEGRAM_PAYOUT_BOT_ID missing)', [
                        'transaction_code' => $transaction->transaction_code ?? null,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('Telegram withdrawal notification failed', [
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
            'remaining_balance' => $remainingBalance,
        ]);
    }

    public function submitOrder(Request $request)
    {
        $request->validate([
            'service_id' => 'nullable|integer',
            'service_name' => 'required|string',
            'user_id_input' => 'required|string',
            'nickname' => 'required|string',
            'nominal' => 'required|string',
            'payment_method' => 'required|string',
            'payment_number' => 'required|string',
            'proof_image' => 'nullable|string',
        ]);

        // Clean nominal string (remove "Rp", ".", and spaces)
        $nominalClean = preg_replace('/[^0-9]/', '', $request->nominal);
        $nominalAmount = (int) $nominalClean;

        // Get commission rate from service (per-service commission from settings)
        $service = null;
        if ($request->filled('service_id')) {
            $service = \App\Models\Service::find($request->service_id);
            if ($service) {
                $service->load('telegramBot');
            }
        }

        // Use service-specific commission rate from settings
        $commissionRate = $service ? $service->commission_rate : 10.00; // Default 10% (more reasonable)
        $commissionAmount = $nominalAmount * ($commissionRate / 100);

        // Generate unique transaction code
        $transactionCode = 'TRX-' . strtoupper(uniqid());

        // Create top-up order as a sale transaction
        $transaction = SaleTransaction::create([
            'transaction_code' => $transactionCode,
            'user_id' => Auth::id(),
            'customer_name' => $request->user_id_input,
            'customer_phone' => $request->nickname,
            'amount' => $nominalAmount > 0 ? $nominalAmount : 0,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'status' => 'pending',
            'transaction_type' => 'topup',
            'description' => "Top Up {$request->service_name} - ID: {$request->user_id_input} - Nickname: {$request->nickname}",
            'payment_method' => $request->payment_method,
            'payment_number' => $request->payment_number,
            'proof_image' => $request->proof_image, // Save proof image
            // Additional fields for admin display
            'user_id_input' => $request->user_id_input,
            'nickname' => $request->nickname,
            'service_name' => $request->service_name,
        ]);

        // Get WhatsApp number (service-specific or global)
        $whatsappNumber = $service && $service->whatsapp_number 
            ? $service->whatsapp_number 
            : \App\Models\Setting::getWhatsAppNumber();

        // Format nominal for display
        $nominalFormatted = 'Rp ' . number_format($nominalAmount, 0, ',', '.');

        // Build Telegram message with transaction details and buttons
        $message = TelegramMessageFormatter::heading('KONFIRMASI TOP UP BARU');
        $message .= TelegramMessageFormatter::bullet('Kode Transaksi', (string) $transactionCode);
        $message .= "\n";
        $message .= TelegramMessageFormatter::bullet('Layanan', (string) $request->service_name);
        $message .= TelegramMessageFormatter::bullet('ID Pengguna', (string) $request->user_id_input);
        $message .= TelegramMessageFormatter::bullet('Nickname', (string) $request->nickname);
        $message .= TelegramMessageFormatter::bullet('Nominal', $nominalFormatted, false);
        $message .= TelegramMessageFormatter::bullet('Pembayaran', (string) $request->payment_method);
        $message .= TelegramMessageFormatter::bullet('No. Rekening', (string) $request->payment_number);
        if ($request->proof_image) {
            $message .= TelegramMessageFormatter::bullet('Bukti Transfer', 'Sudah diunggah');
        }
        $message .= TelegramMessageFormatter::divider();
        $message .= TelegramMessageFormatter::bullet('Status', 'Menunggu konfirmasi admin');

        $botKey = ($service && $service->telegramBot && $service->telegramBot->is_active)
            ? (string) $service->telegramBot->id
            : 'default';

        // Define buttons for Telegram (bot-aware callback_data)
        $buttons = [
            ['text' => '✅ BERHASIL', 'callback_data' => "approve|{$botKey}|{$transactionCode}"],
            ['text' => '❌ GAGAL', 'callback_data' => "reject|{$botKey}|{$transactionCode}"],
        ];

        // Send via Telegram (Primary - with buttons!)
        // Use bot specific to service or fallback to default bot
        $tgService = \App\Services\TelegramService::forService($service);

        // Send with proof image if uploaded
        if ($request->proof_image) {
            $tgResult = $tgService->sendPhotoWithButtons(
                $tgService->getChatId(),
                $message,
                $request->proof_image,
                $buttons
            );
        } else {
            $tgResult = $tgService->sendMessageWithButtons(
                $tgService->getChatId(),
                $message,
                $buttons
            );
        }

        // Store transaction code in cache for webhook callback handling (if success)
        if ($tgResult['success'] && isset($tgResult['message_id'])) {
            cache()->set(
                "tg_message_{$tgResult['message_id']}",
                $transactionCode,
                now()->addHours(24) // Cache for 24 hours
            );

            Log::info('Telegram message tracking stored', [
                'message_id' => $tgResult['message_id'],
                'transaction_code' => $transactionCode,
            ]);
        } else {
            // Log the error
            Log::error('Telegram send failed', [
                'error' => $tgResult['error'] ?? 'Unknown error',
            ]);
        }

        // Also generate fallback WhatsApp URL for manual sending (kept for backward compatibility)
        $fallbackMessage = "🔔 *KONFIRMASI TOP UP BARU* 🔔\n\n";
        $fallbackMessage .= "📋 *Kode Transaksi:* {$transactionCode}\n";
        $fallbackMessage .= "━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $fallbackMessage .= "📱 *Layanan:* {$request->service_name}\n";
        $fallbackMessage .= "🆔 *ID Pengguna:* {$request->user_id_input}\n";
        $fallbackMessage .= "👤 *Nickname:* {$request->nickname}\n";
        $fallbackMessage .= "💰 *Nominal:* {$nominalFormatted}\n";
        $fallbackMessage .= "💳 *Pembayaran:* {$request->payment_method}\n";
        $fallbackMessage .= "🔢 *No. Rekening:* {$request->payment_number}\n";

        if ($request->proof_image) {
            $fallbackMessage .= "📎 *Bukti Transfer:* Sudah diunggah\n";
        }

        $fallbackMessage .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $fallbackMessage .= "⏳ *Status:* Menunggu konfirmasi admin\n\n";
        $fallbackMessage .= "━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $fallbackMessage .= "*Ketik angka untuk memproses:*\n";
        $fallbackMessage .= "1️⃣ *BERHASIL*\n";
        $fallbackMessage .= "2️⃣ *GAGAL*\n\n";
        $fallbackMessage .= "*Contoh ketik:* 1";

        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($fallbackMessage);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'transaction_id' => $transaction->id,
            'transaction_code' => $transactionCode,
            'whatsapp_url' => $whatsappUrl,
            'tg_sent' => $tgResult['success'] ?? false,
            'tg_error' => ($tgResult['success'] ?? false) ? null : ($tgResult['error'] ?? 'Unknown error'),
            'tg_fallback_used' => $tgResult['fallback_used'] ?? false,
            'order_data' => [
                'service' => $request->service_name,
                'user_id' => $request->user_id_input,
                'nickname' => $request->nickname,
                'nominal' => $request->nominal,
                'payment_method' => $request->payment_method,
                'payment_number' => $request->payment_number,
                'proof_image' => $request->proof_image,
            ],
        ]);
    }

    /**
     * Check transaction status for real-time update
     */
    public function checkTransactionStatus($transactionCode)
    {
        $transaction = SaleTransaction::where('transaction_code', $transactionCode)->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan',
            ], 404);
        }

        $statusChanged = false;
        $lastChecked = request('last_checked');
        
        if ($lastChecked && $transaction->updated_at > $lastChecked) {
            $statusChanged = true;
        }

        return response()->json([
            'success' => true,
            'status' => $transaction->status,
            'statusChanged' => $statusChanged,
            'transaction' => [
                'id' => $transaction->id,
                'transaction_code' => $transaction->transaction_code,
                'service_name' => $transaction->service_name,
                'user_id_input' => $transaction->user_id_input,
                'nickname' => $transaction->nickname,
                'amount' => (int) $transaction->amount, // Ensure it's an integer
                'formatted_amount' => 'Rp ' . number_format((int) $transaction->amount, 0, ',', '.'),
                'payment_method' => $transaction->payment_method,
                'payment_number' => $transaction->payment_number,
                'status' => $transaction->status,
                'status_label' => $transaction->status === 'success' ? 'APPROVED' : ($transaction->status === 'failed' ? 'REJECTED' : 'PENDING'),
                'confirmed_at' => $transaction->confirmed_at ? $transaction->confirmed_at->format('d/m/Y H:i:s') : null,
                'completed_at' => $transaction->completed_at ? $transaction->completed_at->format('d/m/Y H:i:s') : null,
            ],
        ]);
    }

    /**
     * Upload user avatar with crop support
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('image')) {
            $user = Auth::user();
            if (! $user instanceof User) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $image = $request->file('image');
            
            // Generate unique filename
            $filename = time() . '_avatar_' . $user->id . '.' . $image->getClientOriginalExtension();
            
            // Save to public/uploads/images/avatar
            $destinationPath = public_path('uploads/images/avatar');
            
            // Create directory if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            // Move the uploaded file
            $image->move($destinationPath, $filename);
            
            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }
            
            // Update user avatar path in database
            $avatarPath = 'uploads/images/avatar/' . $filename;
            $user->update(['avatar' => $avatarPath]);
            
            return response()->json([
                'success' => true,
                'avatar_url' => asset($avatarPath),
                'message' => 'Foto profil berhasil diunggah',
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada file yang diunggah',
        ], 400);
    }

    /**
     * Upload admin avatar with crop support (stores in admin folder)
     */
    public function uploadAdminAvatar(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('image')) {
            $user = Auth::user();
            if (! $user instanceof User) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $image = $request->file('image');
            
            // Generate unique filename with admin prefix
            $filename = time() . '_admin_avatar_' . $user->id . '.' . $image->getClientOriginalExtension();
            
            // Save to public/uploads/images/avatar/admin
            $destinationPath = public_path('uploads/images/avatar/admin');
            
            // Create directory if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            // Move the uploaded file
            $image->move($destinationPath, $filename);
            
            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }
            
            // Update user avatar path in database
            $avatarPath = 'uploads/images/avatar/admin/' . $filename;
            $user->update(['avatar' => $avatarPath]);
            
            return response()->json([
                'success' => true,
                'avatar_url' => asset($avatarPath),
                'message' => 'Foto profil admin berhasil diunggah',
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada file yang diunggah',
        ], 400);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
        ]);
        
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'user' => [
                'name' => $user->name,
                'username' => $user->username,
                'avatar' => $user->avatar ? asset($user->avatar) : null,
            ],
        ]);
    }
}
