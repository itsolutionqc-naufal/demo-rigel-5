<?php

namespace Database\Seeders;

use App\Models\SaleTransaction;
use App\Models\User;
use App\Services\CommissionService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MarketingDashboardDemoSeeder extends Seeder
{
    /**
     * NOTE:
     * - This seeder deletes rows by tag and is intended for "resettable demo" scenarios.
     * - If you want a non-destructive seed (only add, never delete), use:
     *   `php artisan db:seed --class=MarketingDashboardAdditiveSeeder`
     */
    public function run(): void
    {
        $tag = '[DEMO MKT DASH]';

        $marketingEmail = 'marketing@gmail.com';
        $marketing = User::where('email', $marketingEmail)->first();

        if (! $marketing) {
            $attributes = [
                'name' => 'Marketing',
                'email' => $marketingEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('marketing123'),
                'role' => User::ROLE_MARKETING,
            ];

            if (Schema::hasColumn('users', 'username')) {
                $baseUsername = 'marketing';
                $username = $baseUsername;
                if (User::where('username', $username)->exists()) {
                    $username = $baseUsername.'_'.Str::lower(Str::random(4));
                }
                $attributes['username'] = $username;
            }

            if (Schema::hasColumn('users', 'avatar')) {
                $attributes['avatar'] = null;
            }

            $marketing = User::create($attributes);
        }

        $managed1 = User::updateOrCreate(
            ['email' => 'mkt_user1@gmail.com'],
            [
                'name' => 'MKT User 1',
                'email_verified_at' => now(),
                'password' => 'password',
                'role' => User::ROLE_USER,
                'marketing_owner_id' => $marketing->id,
            ]
        );

        $managed2 = User::updateOrCreate(
            ['email' => 'mkt_user2@gmail.com'],
            [
                'name' => 'MKT User 2',
                'email_verified_at' => now(),
                'password' => 'password',
                'role' => User::ROLE_USER,
                'marketing_owner_id' => $marketing->id,
            ]
        );

        SaleTransaction::query()
            ->where('description', 'like', $tag.'%')
            ->delete();

        $service = app(CommissionService::class);

        $rows = [
            [$managed1, '2026-04-02 10:05:00', 120_000, 5.0],
            [$managed2, '2026-04-03 12:15:00', 180_000, 4.0],
            [$managed1, '2026-04-05 09:30:00', 250_000, 5.0],
            [$managed2, '2026-04-07 16:40:00', 320_000, 4.0],
            [$managed1, '2026-04-10 11:10:00', 150_000, 3.0],
            [$managed2, '2026-04-12 20:25:00', 410_000, 4.0],
        ];

        foreach ($rows as [$owner, $dateTime, $amount, $rate]) {
            $createdAt = Carbon::parse($dateTime);
            $commissionAmount = round($amount * ($rate / 100), 2);

            $tx = SaleTransaction::create([
                'user_id' => $owner->id,
                'customer_name' => $owner->name,
                'customer_phone' => '081234567890',
                'amount' => $amount,
                'commission_rate' => $rate,
                'commission_amount' => $commissionAmount,
                'status' => 'success',
                'transaction_type' => 'transaction',
                'description' => $tag.' seeded transaction',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => (string) $owner->id,
                'nickname' => $owner->username ?? $owner->name,
                'service_name' => 'Demo Service',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'completed_at' => $createdAt,
            ]);

            $service->calculateAndCreateCommission($tx);
        }

        SaleTransaction::create([
            'user_id' => $managed1->id,
            'customer_name' => $managed1->name,
            'customer_phone' => '081234567890',
            'amount' => 99_000,
            'commission_rate' => 5.0,
            'commission_amount' => 4_950,
            'status' => 'failed',
            'transaction_type' => 'transaction',
            'description' => $tag.' seeded failed',
            'payment_method' => 'BCA',
            'payment_number' => '1234567890',
            'user_id_input' => (string) $managed1->id,
            'nickname' => $managed1->username ?? $managed1->name,
            'service_name' => 'Demo Service',
            'created_at' => Carbon::parse('2026-04-11 13:00:00'),
            'updated_at' => Carbon::parse('2026-04-11 13:00:00'),
        ]);
    }
}
