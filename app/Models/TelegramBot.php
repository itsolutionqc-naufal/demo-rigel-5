<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TelegramBot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'token',
        'chat_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the services that use this bot.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'telegram_bot_id');
    }

    /**
     * Get active bots.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
