<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sale_transaction_id',
        'amount',
        'period_date',
        'period_type',
        'withdrawn',
        'withdrawal_transaction_id',
    ];

    protected $casts = [
        'amount' => 'float',
        'period_date' => 'date',
    ];

    /**
     * Get the user who received the commission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sale transaction that generated this commission.
     */
    public function saleTransaction(): BelongsTo
    {
        return $this->belongsTo(SaleTransaction::class);
    }

    public function withdrawalTransaction(): BelongsTo
    {
        return $this->belongsTo(SaleTransaction::class, 'withdrawal_transaction_id');
    }
}
