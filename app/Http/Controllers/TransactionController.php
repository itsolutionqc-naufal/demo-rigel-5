<?php

namespace App\Http\Controllers;

use App\Models\SaleTransaction;
use App\Models\PaymentMethod;
use App\Services\CommissionWithdrawalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    private function transactionsIndexRouteName(): string
    {
        $user = Auth::user();

        if ($user && $user->isMarketing()) {
            return 'marketing.transactions.index';
        }

        return 'transactions.index';
    }

    /**
     * Display a listing of all sale transactions (history).
     */
    public function index()
    {
        $this->authorize('viewAny', SaleTransaction::class);

        $actor = Auth::user();
        $baseQuery = SaleTransaction::query();

        if ($actor && $actor->isMarketing()) {
            $baseQuery->visibleToMarketing($actor);
        }

        $metrics = [
            'total_transactions' => (clone $baseQuery)->count(),
            'success_transactions' => (clone $baseQuery)->where('status', 'success')->count(),
            'total_amount' => (float) (clone $baseQuery)->sum('amount'),
            'process_transactions' => (clone $baseQuery)->where('status', 'process')->count(),
        ];

	        $query = SaleTransaction::with(['user', 'admin'])
	            ->select('id', 'user_id', 'user_id_input', 'nickname', 'customer_name', 'customer_phone',
	                     'service_name', 'transaction_type', 'amount', 'commission_rate', 'commission_amount',
	                     'status', 'payment_method', 'payment_number', 'proof_image', 'description',
	                     'bank_name', 'account_number', 'account_name', 'whatsapp_number', 'address',
	                     'admin_id', 'confirmed_at', 'completed_at',
	                     'created_at', 'updated_at')
	            ->orderBy('created_at', 'desc')
	            ;

        if ($actor && $actor->isMarketing()) {
            $query->visibleToMarketing($actor);
        }

        $transactions = $query->paginate(10);

        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('transactions.index', compact('transactions', 'paymentMethods', 'metrics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', SaleTransaction::class);

        $validated = $request->validate([
            'user_id_input' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'service_name' => 'nullable|string|max:255',
            'transaction_type' => 'required|in:topup,withdrawal,transaction',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['commission_rate'] = $validated['commission_rate'] ?? 0;
        $validated['commission_amount'] = $validated['amount'] * ($validated['commission_rate'] / 100);
        $validated['status'] = 'pending'; // Default status untuk transaksi baru

        $saleTransaction = SaleTransaction::create($validated);

        return redirect()->route($this->transactionsIndexRouteName())->with('success', 'Data transaksi berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaleTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->refresh();
        $originalStatus = (string) ($transaction->status ?? '');

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:pending,process,success,failed',
        ]);

        $newStatus = (string) $validated['status'];
        if (($transaction->transaction_type ?? null) === 'withdrawal'
            && in_array($originalStatus, ['success', 'failed'], true)
            && $newStatus !== $originalStatus) {

            return redirect()->back()->with('error', 'Status penarikan sudah final dan tidak bisa diubah lagi.');
        }

        $validated['commission_amount'] = $validated['amount'] * ($validated['commission_rate'] / 100);

        DB::transaction(function () use ($transaction, $validated, $originalStatus, $newStatus) {
            if (($transaction->transaction_type ?? null) === 'withdrawal'
                && $newStatus === 'failed'
                && $originalStatus !== 'failed') {
                app(CommissionWithdrawalService::class)->restoreForWithdrawal($transaction);
            }

            $transaction->update($validated);
        });

        return redirect()->route($this->transactionsIndexRouteName())->with('success', 'Data transaksi berhasil diperbarui.');
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, SaleTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        // Refresh the transaction to ensure it's up to date
        $transaction->refresh();
        $originalStatus = (string) ($transaction->status ?? '');

        $validated = $request->validate([
            'status' => 'required|in:pending,process,success,failed',
        ]);

        $newStatus = (string) $validated['status'];
        if (($transaction->transaction_type ?? null) === 'withdrawal'
            && in_array($originalStatus, ['success', 'failed'], true)
            && $newStatus !== $originalStatus) {

            if ($request->ajax() || $request->hasHeader('X-Requested-With')) {
                return response()->json([
                    'message' => 'Status penarikan sudah final dan tidak bisa diubah lagi.',
                ], 422);
            }

            return redirect()->back()->with('error', 'Status penarikan sudah final dan tidak bisa diubah lagi.');
        }

        // Calculate commission amount if status is changing to 'success' and commission amount is not set
        if ($validated['status'] === 'success' && (!$transaction->commission_amount || $transaction->commission_amount == 0)) {
            $calculatedCommission = $transaction->amount * ($transaction->commission_rate / 100);
            $validated['commission_amount'] = $calculatedCommission;
        }

        // Update the status and set completed_at timestamp
        $validated['completed_at'] = $validated['status'] === 'success' ? now() : null;

        DB::transaction(function () use ($transaction, $validated, $originalStatus, $newStatus) {
            if (($transaction->transaction_type ?? null) === 'withdrawal'
                && $newStatus === 'failed'
                && $originalStatus !== 'failed') {
                app(CommissionWithdrawalService::class)->restoreForWithdrawal($transaction);
            }

            $transaction->update($validated);
        });
        // Note: Commission is automatically handled by SaleTransaction model observer

        if ($request->ajax() || $request->hasHeader('X-Requested-With')) {
            return response()->json(['message' => 'Status transaksi berhasil diperbarui.']);
        }

        return redirect()->route($this->transactionsIndexRouteName())->with('success', 'Status transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleTransaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus.');
    }

    /**
     * Get the specified transaction for API.
     */
    public function show(SaleTransaction $transaction)
    {
        $this->authorize('view', $transaction);

        $transaction->load(['user' => function ($query) {
            $query->select('id', 'name', 'username', 'email', 'avatar', 'role');
        }, 'admin' => function ($query) {
            $query->select('id', 'name', 'username', 'email', 'avatar', 'role');
        }]);

        return response()->json([
            'success' => true,
            'transaction' => $transaction
        ]);
    }
}
