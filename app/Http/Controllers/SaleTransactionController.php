<?php

namespace App\Http\Controllers;

use App\Models\SaleTransaction;
use App\Models\PaymentMethod;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SaleTransactionController extends Controller
{
    use AuthorizesRequests;

    private function salesIndexRouteName(): string
    {
        $user = Auth::user();

        if ($user && $user->isMarketing()) {
            return 'marketing.sales.index';
        }

        return 'sales.index';
    }

    /**
     * Display a listing of all sale transactions (history).
     */
    public function index()
    {
        $this->authorize('viewAny', SaleTransaction::class);

        $actor = Auth::user();
        $query = SaleTransaction::with('user', 'admin')
            ->orderBy('created_at', 'desc')
            ;

        if ($actor && $actor->isMarketing()) {
            $query->visibleToMarketing($actor);
        }

        $sales = $query->paginate(10);

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', SaleTransaction::class);

        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('sales.create', compact('paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', SaleTransaction::class);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'process'; // Default to process status
        $validated['commission_amount'] = $validated['amount'] * ($validated['commission_rate'] / 100);

        $saleTransaction = SaleTransaction::create($validated);

        return redirect()->route($this->salesIndexRouteName())->with('success', 'Data pesanan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaleTransaction $saleTransaction)
    {
        $this->authorize('view', $saleTransaction);

        return view('sales.show', compact('saleTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaleTransaction $saleTransaction)
    {
        $this->authorize('update', $saleTransaction);

        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('sales.edit', compact('saleTransaction', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaleTransaction $saleTransaction)
    {
        $this->authorize('update', $saleTransaction);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:process,success,failed',
        ]);

        // Calculate commission amount for reference (not used for commission creation, handled by observer)
        $validated['commission_amount'] = $validated['amount'] * ($validated['commission_rate'] / 100);

        // Set completed_at timestamp when status changes to success
        if ($validated['status'] === 'success' && $saleTransaction->status !== 'success') {
            $validated['completed_at'] = now();
            $validated['admin_id'] = Auth::id();
        } elseif ($validated['status'] !== 'success' && $saleTransaction->status === 'success') {
            $validated['completed_at'] = null;
        }

        $saleTransaction->update($validated);
        // Note: Commission is automatically handled by SaleTransaction model observer

        return redirect()->route($this->salesIndexRouteName())->with('success', 'Data pesanan berhasil diperbarui.');
    }

    /**
     * Approve the specified transaction (set status to success).
     */
    public function approve(SaleTransaction $saleTransaction)
    {
        $this->authorize('update', $saleTransaction);

        $saleTransaction->update([
            'status' => 'success',
            'admin_id' => Auth::id(),
            'completed_at' => now(),
        ]);
        // Note: Commission is automatically created by SaleTransaction model observer

        return redirect()->back()->with('success', 'Transaksi berhasil disetujui.');
    }

    /**
     * Reject the specified transaction (set status to failed).
     */
    public function reject(SaleTransaction $saleTransaction)
    {
        $this->authorize('update', $saleTransaction);

        $saleTransaction->update([
            'status' => 'failed',
            'admin_id' => Auth::id(),
        ]);
        // Note: Commission is automatically removed by SaleTransaction model observer

        return redirect()->back()->with('success', 'Transaksi berhasil ditolak.');
    }

    /**
     * Process the specified transaction (set status to process).
     */
    public function process(SaleTransaction $saleTransaction)
    {
        $this->authorize('update', $saleTransaction);

        $saleTransaction->update([
            'status' => 'process',
            'admin_id' => Auth::id(),
        ]);
        // Note: Commission is automatically removed by SaleTransaction model observer

        return redirect()->back()->with('success', 'Transaksi dikembalikan ke status proses.');
    }

    /**
     * Change the status of the specified resource.
     */
    public function updateStatus(Request $request, SaleTransaction $saleTransaction)
    {
        $this->authorize('update', $saleTransaction);

        $saleTransaction->refresh();
        $originalStatus = (string) ($saleTransaction->status ?? '');

        $validated = $request->validate([
            'status' => 'required|in:process,success,failed',
        ]);

        $newStatus = (string) $validated['status'];
        if ($newStatus === $originalStatus) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak berubah. Pilih status lain untuk mengirim notifikasi.',
            ], 422);
        }

        $saleTransaction->update([
            'status' => $newStatus,
            'admin_id' => Auth::id(),
            'completed_at' => $newStatus === 'success' ? now() : null,
        ]);
        // Note: Commission is automatically handled by SaleTransaction model observer

        $dispatchResult = NotificationService::takeLastDispatchResult((int) $saleTransaction->id);
        if (! $dispatchResult) {
            $dispatchResult = app(NotificationService::class)->notifyTransactionStatusChange(
                $saleTransaction->fresh(['user', 'admin']),
                $originalStatus,
                $newStatus
            );

            Log::warning('Notification fallback dispatch executed (sales)', [
                'transaction_id' => (int) $saleTransaction->id,
                'old_status' => $originalStatus,
                'new_status' => $newStatus,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui.',
            'fcm_sent' => (bool) ($dispatchResult['fcm_sent'] ?? false),
            'fcm_error' => $dispatchResult['fcm_error'] ?? null,
            'new_status' => $newStatus,
        ]);
    }
}
