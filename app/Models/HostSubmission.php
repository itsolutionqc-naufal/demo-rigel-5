<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_transaction_id',
        'service_id',
        'host_id',
        'nickname',
        'whatsapp_number',
        'form_filled',
    ];

    protected $casts = [
        'form_filled' => 'boolean',
    ];

    public function saleTransaction(): BelongsTo
    {
        return $this->belongsTo(SaleTransaction::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}

