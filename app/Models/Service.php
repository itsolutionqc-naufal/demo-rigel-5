<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'image',
        'price',
        'status',
        'is_active',
        'commission_rate',
        'whatsapp_number',
        'telegram_chat_id',
        'telegram_bot_id',
        'minimum_nominal',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'minimum_nominal' => 'integer',
    ];

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * Get the Telegram bot associated with this service.
     */
    public function telegramBot()
    {
        return $this->belongsTo(TelegramBot::class);
    }
}
