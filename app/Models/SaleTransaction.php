<?php

namespace App\Models;

use App\Services\CommissionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class SaleTransaction extends Model
{
    use HasFactory;

    protected $table = 'sale_transactions';

    protected $fillable = [
        'transaction_code',
        'customer_name',
        'customer_phone',
        'amount',
        'commission_rate',
        'commission_amount',
        'status',
        'user_id',
        'admin_id',
        'confirmed_at',
        'completed_at',
        'transaction_type',
        'description',
        'payment_method',
        'payment_number',
        'bank_name',
        'account_number',
        'account_name',
        'whatsapp_number',
        'address',
        'proof_image',
        'user_id_input',
        'nickname',
        'service_name',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Boot the model and attach event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($saleTransaction) {
            // Check if status changed
            if ($saleTransaction->isDirty('status')) {
                $originalStatus = $saleTransaction->getOriginal('status');

                if ($saleTransaction->status === 'success' && $originalStatus !== 'success') {
                    // Calculate and create commission when status changes to 'success'
                    $service = app(CommissionService::class);
                    $service->calculateAndCreateCommission($saleTransaction);

                    // Set completed_at timestamp if not already set by controller
                    if (!$saleTransaction->completed_at) {
                        $saleTransaction->completed_at = now();
                        $saleTransaction->saveQuietly();
                    }

                    // Send notification about status change
                    $notificationService = app(\App\Services\NotificationService::class);
                    $notificationService->notifyTransactionStatusChange($saleTransaction, $originalStatus, $saleTransaction->status);
                } elseif ($originalStatus === 'success' && $saleTransaction->status !== 'success') {
                    // Remove commission when status changes from 'success' to something else
                    $saleTransaction->commissions()->delete();

                    // Clear completed_at timestamp if not already handled by controller
                    if ($saleTransaction->completed_at !== null) {
                        $saleTransaction->completed_at = null;
                        $saleTransaction->saveQuietly();
                    }

                    // Send notification about status change
                    $notificationService = app(\App\Services\NotificationService::class);
                    $notificationService->notifyTransactionStatusChange($saleTransaction, $originalStatus, $saleTransaction->status);
                } else {
                    // Send notification for other status changes (like process to failed, etc.)
                    $notificationService = app(\App\Services\NotificationService::class);
                    $notificationService->notifyTransactionStatusChange($saleTransaction, $originalStatus, $saleTransaction->status);
                }
            }

            // Check if transaction details changed when status is 'success' (recalculate commission)
            // Skip if status also changed (to avoid double processing)
            if ($saleTransaction->status === 'success' &&
                !$saleTransaction->isDirty('status') &&
                ($saleTransaction->isDirty('amount') || $saleTransaction->isDirty('commission_rate'))) {

                $service = app(CommissionService::class);
                $service->calculateAndCreateCommission($saleTransaction);
            }
        });
    }

    /**
     * Get the user that created the sale transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVisibleToMarketing(Builder $query, User $marketing): Builder
    {
        return $query->where(function (Builder $scoped) use ($marketing) {
            $scoped->where('user_id', $marketing->id)
                ->orWhereIn('user_id', User::query()
                    ->select('id')
                    ->where('marketing_owner_id', $marketing->id));
        });
    }

    /**
     * Get the admin who confirmed the sale transaction.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the payment method used for this sale transaction.
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    /**
     * Get the service associated with this transaction (if any).
     * Note: This uses service_name field, not a foreign key relationship.
     */
    public function service()
    {
        return $this->hasOne(Service::class, 'name', 'service_name');
    }

    /**
     * Get the commission records for this sale transaction.
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function hostSubmission()
    {
        return $this->hasOne(HostSubmission::class);
    }
}
