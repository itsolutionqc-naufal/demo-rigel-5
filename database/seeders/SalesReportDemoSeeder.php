<?php

namespace Database\Seeders;

use Carbon\CarbonInterface;
use App\Models\PaymentMethod;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SalesReportDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'username' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'username' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Ensure at least one active bank account and QRIS show up on /reports/sales.
        if (! PaymentMethod::where('is_active', true)->where('type', 'bank_account')->exists()) {
            PaymentMethod::create([
                'name' => 'BCA - Rigel Agency',
                'type' => 'bank_account',
                'account_number' => '1234567890',
                'account_holder' => 'Rigel Agency',
                'is_active' => true,
            ]);
        }

        if (! PaymentMethod::where('is_active', true)->where('type', 'qris')->exists()) {
            PaymentMethod::create([
                'name' => 'QRIS Rigel Agency',
                'type' => 'qris',
                'qr_code_path' => null,
                'nmid' => 'ID1234567890123',
                'is_active' => true,
            ]);
        }

        // Clean up previous demo rows so re-running the seeder is safe.
        $tag = '[DEMO SALES REPORT]';
        SaleTransaction::query()
            ->where('description', 'like', $tag.'%')
            ->delete();

        // Seed a small, predictable dataset so the /reports/sales charts + table look "alive".
        // - Daily view uses created_at hour buckets.
        // - Monthly view uses created_at date buckets.
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth()->startOfDay();

        $this->seedDay($user, $today, [
            ['09:10:00', 150_000, 5.0],
            ['09:45:00', 250_000, 5.0],
            ['14:05:00', 300_000, 4.0],
            ['20:30:00', 500_000, 3.0],
        ]);

        $this->seedDay($admin, $today, [
            ['10:15:00', 125_000, 2.5],
            ['16:40:00', 275_000, 2.5],
        ]);

        // Spread a few successful transactions across the month.
        $this->seedDay($user, $monthStart->copy()->addDays(0), [
            ['11:00:00', 100_000, 5.0],
        ]);

        $this->seedDay($user, $monthStart->copy()->addDays(2), [
            ['13:20:00', 200_000, 4.0],
            ['18:05:00', 350_000, 4.0],
        ]);

        $this->seedDay($admin, $monthStart->copy()->addDays(4), [
            ['09:30:00', 400_000, 2.0],
            ['15:10:00', 600_000, 2.0],
            ['21:55:00', 250_000, 2.0],
        ]);

        $this->seedDay($user, $monthStart->copy()->addDays(6), [
            ['08:05:00', 175_000, 3.5],
            ['19:25:00', 225_000, 3.5],
        ]);
    }

    /**
     * @param  array<int, array{0:string,1:int,2:float}>  $rows
     */
    private function seedDay(User $owner, CarbonInterface $day, array $rows): void
    {
        foreach ($rows as [$time, $amount, $commissionRate]) {
            $createdAt = $day->copy()->setTimeFromTimeString($time);
            $commissionAmount = round($amount * ($commissionRate / 100), 2);

            SaleTransaction::create([
                'user_id' => $owner->id,
                'customer_name' => $owner->name,
                'customer_phone' => '081234567890',
                'amount' => $amount,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'status' => 'success',
                'transaction_type' => 'topup',
                'description' => '[DEMO SALES REPORT] Example transaction',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => (string) $owner->id,
                'nickname' => $owner->username ?? $owner->name,
                'service_name' => 'Demo Service',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'completed_at' => $createdAt,
            ]);
        }
    }
}
