<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_USER = 'user';

    public const ROLE_MARKETING = 'marketing';

    protected static function booted(): void
    {
        static::saving(function (self $user) {
            if ($user->role !== self::ROLE_USER) {
                $user->marketing_owner_id = null;
            }
        });
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isMarketing(): bool
    {
        return $this->role === self::ROLE_MARKETING;
    }

    public function homeRouteName(): string
    {
        if ($this->isAdmin()) {
            return 'dashboard';
        }

        if ($this->isMarketing()) {
            return 'marketing.dashboard';
        }

        return 'mobile.app';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'avatar',
        'marketing_owner_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the articles for the user.
     */
    public function articles()
    {
        return $this->hasMany(\App\Models\Article::class);
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }

    /**
     * Get the sales transactions created by the user.
     */
    public function salesTransactions()
    {
        return $this->hasMany(\App\Models\SaleTransaction::class);
    }

    /**
     * Get the commissions received by the user.
     */
    public function commissions()
    {
        return $this->hasMany(\App\Models\Commission::class);
    }

    public function marketingOwner(): BelongsTo
    {
        return $this->belongsTo(self::class, 'marketing_owner_id');
    }

    public function managedUsers(): HasMany
    {
        return $this->hasMany(self::class, 'marketing_owner_id');
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function isManagedBy(User $marketing): bool
    {
        return (int) $this->marketing_owner_id === (int) $marketing->id;
    }
}
