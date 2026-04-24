<?php

namespace Database\Seeders;

use App\Models\SaleTransaction;
use App\Models\User;
use App\Services\CommissionService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class MarketingDashboardAdditiveSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasColumn('users', 'marketing_owner_id')) {
            $this->command?->warn('Skipping MarketingDashboardAdditiveSeeder: users.marketing_owner_id column not found.');
            return;
        }

        $marketing = User::where('email', 'marketing@gmail.com')->first();

        if (! $marketing) {
            $attributes = [
                'name' => 'Marketing',
                'email' => 'marketing@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('marketing123'),
                'role' => User::ROLE_MARKETING,
            ];

            if (Schema::hasColumn('users', 'username')) {
                $attributes['username'] = 'marketing';
            }

            if (Schema::hasColumn('users', 'avatar')) {
                $attributes['avatar'] = null;
            }

            $marketing = User::create($attributes);
        }

        // Create managed users (real names, non-random). Additive: never overwrite existing users.
        $managedUsers = [];
        foreach ($this->managedUserSeeds() as $seed) {
            $managedUsers[] = User::firstOrCreate(
                ['email' => $seed['email']],
                [
                    'name' => $seed['name'],
                    'username' => Schema::hasColumn('users', 'username') ? $seed['username'] : null,
                    'role' => User::ROLE_USER,
                    'marketing_owner_id' => $marketing->id,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ]
            );
        }

        $service = app(CommissionService::class);

        foreach ($this->transactionSeeds($managedUsers) as $seed) {
            $createdAt = Carbon::parse($seed['created_at']);
            $amount = (float) $seed['amount'];
            $rate = (float) $seed['commission_rate'];
            $commissionAmount = round($amount * ($rate / 100), 2);

            $tx = SaleTransaction::firstOrCreate(
                ['transaction_code' => $seed['transaction_code']],
                [
                    'user_id' => $seed['user_id'],
                    'customer_name' => $seed['customer_name'],
                    'customer_phone' => $seed['customer_phone'],
                    'amount' => $amount,
                    'commission_rate' => $rate,
                    'commission_amount' => $commissionAmount,
                    'status' => $seed['status'],
                    'transaction_type' => 'transaction', // important: non-wallet
                    'description' => $seed['description'],
                    'payment_method' => $seed['payment_method'],
                    'payment_number' => $seed['payment_number'],
                    'user_id_input' => $seed['user_id_input'],
                    'nickname' => $seed['nickname'],
                    'service_name' => $seed['service_name'],
                    'completed_at' => $seed['status'] === 'success' ? $createdAt : null,
                ]
            );

            // Ensure chart time buckets land in April 2026 (only for newly created demo rows).
            if ($tx->wasRecentlyCreated) {
                $tx->created_at = $createdAt;
                $tx->updated_at = $createdAt;
                $tx->saveQuietly();
            }

            if ($tx->wasRecentlyCreated && $tx->status === 'success') {
                $service->calculateAndCreateCommission($tx);
            }
        }

        $this->command?->info('Marketing dashboard additive demo seeded (no existing data deleted).');
    }

    /**
     * @return array<int, array{name:string,email:string,username:string}>
     */
    private function managedUserSeeds(): array
    {
        return [
            ['email' => 'budi.santoso@rigelcoin.com', 'name' => 'Budi Santoso', 'username' => 'budi.santoso'],
            ['email' => 'siti.aisyah@rigelcoin.com', 'name' => 'Siti Aisyah', 'username' => 'siti.aisyah'],
            ['email' => 'raka.pratama@rigelcoin.com', 'name' => 'Raka Pratama', 'username' => 'raka.pratama'],
            ['email' => 'nadia.putri@rigelcoin.com', 'name' => 'Nadia Putri', 'username' => 'nadia.putri'],
            ['email' => 'fajar.hidayat@rigelcoin.com', 'name' => 'Fajar Hidayat', 'username' => 'fajar.hidayat'],
        ];
    }

    /**
     * @param  array<int, \App\Models\User>  $managedUsers
     * @return array<int, array<string, mixed>>
     */
    private function transactionSeeds(array $managedUsers): array
    {
        $byEmail = collect($managedUsers)->keyBy('email');

        $pick = function (string $email) use ($byEmail): User {
            /** @var User|null $user */
            $user = $byEmail->get($email);
            if (! $user) {
                throw new \RuntimeException("Managed user missing for seed email: {$email}");
            }
            return $user;
        };

        $serviceName = 'Demo Non-Wallet';

        return [
            [
                'transaction_code' => 'DEMO-MKT-20260402-01',
                'user_id' => $pick('budi.santoso@rigelcoin.com')->id,
                'customer_name' => 'Budi Santoso',
                'customer_phone' => '081234567801',
                'amount' => 120_000,
                'commission_rate' => 5.0,
                'status' => 'success',
                'description' => '[ADD MKT DASH] Demo transaction',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => '10101010',
                'nickname' => 'Budi',
                'service_name' => $serviceName,
                'created_at' => '2026-04-02 10:05:00',
            ],
            [
                'transaction_code' => 'DEMO-MKT-20260403-01',
                'user_id' => $pick('siti.aisyah@rigelcoin.com')->id,
                'customer_name' => 'Siti Aisyah',
                'customer_phone' => '081234567802',
                'amount' => 180_000,
                'commission_rate' => 4.0,
                'status' => 'success',
                'description' => '[ADD MKT DASH] Demo transaction',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => '20202020',
                'nickname' => 'Siti',
                'service_name' => $serviceName,
                'created_at' => '2026-04-03 12:15:00',
            ],
            [
                'transaction_code' => 'DEMO-MKT-20260405-01',
                'user_id' => $pick('raka.pratama@rigelcoin.com')->id,
                'customer_name' => 'Raka Pratama',
                'customer_phone' => '081234567803',
                'amount' => 250_000,
                'commission_rate' => 5.0,
                'status' => 'success',
                'description' => '[ADD MKT DASH] Demo transaction',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => '30303030',
                'nickname' => 'Raka',
                'service_name' => $serviceName,
                'created_at' => '2026-04-05 09:30:00',
            ],
            [
                'transaction_code' => 'DEMO-MKT-20260407-01',
                'user_id' => $pick('nadia.putri@rigelcoin.com')->id,
                'customer_name' => 'Nadia Putri',
                'customer_phone' => '081234567804',
                'amount' => 320_000,
                'commission_rate' => 4.0,
                'status' => 'success',
                'description' => '[ADD MKT DASH] Demo transaction',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => '40404040',
                'nickname' => 'Nadia',
                'service_name' => $serviceName,
                'created_at' => '2026-04-07 16:40:00',
            ],
            [
                'transaction_code' => 'DEMO-MKT-20260410-01',
                'user_id' => $pick('fajar.hidayat@rigelcoin.com')->id,
                'customer_name' => 'Fajar Hidayat',
                'customer_phone' => '081234567805',
                'amount' => 150_000,
                'commission_rate' => 3.0,
                'status' => 'success',
                'description' => '[ADD MKT DASH] Demo transaction',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => '50505050',
                'nickname' => 'Fajar',
                'service_name' => $serviceName,
                'created_at' => '2026-04-10 11:10:00',
            ],
            // One failed transaction to light up the "Transaksi Gagal" card.
            [
                'transaction_code' => 'DEMO-MKT-20260411-01',
                'user_id' => $pick('budi.santoso@rigelcoin.com')->id,
                'customer_name' => 'Budi Santoso',
                'customer_phone' => '081234567801',
                'amount' => 99_000,
                'commission_rate' => 5.0,
                'status' => 'failed',
                'description' => '[ADD MKT DASH] Demo failed transaction',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => '10101010',
                'nickname' => 'Budi',
                'service_name' => $serviceName,
                'created_at' => '2026-04-11 13:00:00',
            ],
            // Seed a "daily" dataset for the sales report (hour buckets) on 2026-04-14.
            [
                'transaction_code' => 'DEMO-MKT-20260414-01',
                'user_id' => $pick('budi.santoso@rigelcoin.com')->id,
                'customer_name' => 'Budi Santoso',
                'customer_phone' => '081234567801',
                'amount' => 135_000,
                'commission_rate' => 5.0,
                'status' => 'success',
                'description' => '[ADD MKT DASH] Demo sales report (daily)',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => '10101010',
                'nickname' => 'Budi',
                'service_name' => $serviceName,
                'created_at' => '2026-04-14 08:15:00',
            ],
            [
                'transaction_code' => 'DEMO-MKT-20260414-02',
                'user_id' => $pick('siti.aisyah@rigelcoin.com')->id,
                'customer_name' => 'Siti Aisyah',
                'customer_phone' => '081234567802',
                'amount' => 210_000,
                'commission_rate' => 4.0,
                'status' => 'success',
                'description' => '[ADD MKT DASH] Demo sales report (daily)',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => '20202020',
                'nickname' => 'Siti',
                'service_name' => $serviceName,
                'created_at' => '2026-04-14 10:05:00',
            ],
            [
                'transaction_code' => 'DEMO-MKT-20260414-03',
                'user_id' => $pick('raka.pratama@rigelcoin.com')->id,
                'customer_name' => 'Raka Pratama',
                'customer_phone' => '081234567803',
                'amount' => 175_000,
                'commission_rate' => 5.0,
                'status' => 'success',
                'description' => '[ADD MKT DASH] Demo sales report (daily)',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => '30303030',
                'nickname' => 'Raka',
                'service_name' => $serviceName,
                'created_at' => '2026-04-14 12:30:00',
            ],
            [
                'transaction_code' => 'DEMO-MKT-20260414-04',
                'user_id' => $pick('nadia.putri@rigelcoin.com')->id,
                'customer_name' => 'Nadia Putri',
                'customer_phone' => '081234567804',
                'amount' => 290_000,
                'commission_rate' => 4.0,
                'status' => 'success',
                'description' => '[ADD MKT DASH] Demo sales report (daily)',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => '40404040',
                'nickname' => 'Nadia',
                'service_name' => $serviceName,
                'created_at' => '2026-04-14 15:45:00',
            ],
            [
                'transaction_code' => 'DEMO-MKT-20260414-05',
                'user_id' => $pick('fajar.hidayat@rigelcoin.com')->id,
                'customer_name' => 'Fajar Hidayat',
                'customer_phone' => '081234567805',
                'amount' => 420_000,
                'commission_rate' => 3.0,
                'status' => 'success',
                'description' => '[ADD MKT DASH] Demo sales report (daily)',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => '50505050',
                'nickname' => 'Fajar',
                'service_name' => $serviceName,
                'created_at' => '2026-04-14 19:20:00',
            ],
            [
                'transaction_code' => 'DEMO-MKT-20260414-06',
                'user_id' => $pick('budi.santoso@rigelcoin.com')->id,
                'customer_name' => 'Budi Santoso',
                'customer_phone' => '081234567801',
                'amount' => 160_000,
                'commission_rate' => 5.0,
                'status' => 'success',
                'description' => '[ADD MKT DASH] Demo sales report (daily)',
                'payment_method' => 'BCA',
                'payment_number' => '1234567890',
                'user_id_input' => '10101010',
                'nickname' => 'Budi',
                'service_name' => $serviceName,
                'created_at' => '2026-04-14 21:10:00',
            ],
        ];
    }
}
