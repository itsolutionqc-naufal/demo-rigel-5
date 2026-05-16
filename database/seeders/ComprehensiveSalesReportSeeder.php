<?php

namespace Database\Seeders;

use App\Models\Commission;
use App\Models\PaymentMethod;
use App\Models\SaleTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class ComprehensiveSalesReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Schema::hasColumn('users', 'marketing_owner_id')) {
            $this->command?->warn('Skipping: users.marketing_owner_id column not found.');
            return;
        }

        $this->command?->info('🚀 Starting Comprehensive Sales Report Seeder...');

        // 1. Create Marketing User
        $marketing = $this->createMarketingUser();
        $this->command?->info("✅ Marketing user created: {$marketing->email}");

        // 2. Create Managed Users
        $managedUsers = $this->createManagedUsers($marketing);
        $this->command?->info('✅ ' . count($managedUsers) . ' managed users created/verified.');

        // 3. Ensure Payment Methods Exist
        $this->ensurePaymentMethods();
        $this->command?->info('✅ Payment methods verified.');

        // 4. Clean old demo data
        $this->cleanOldData();
        $this->command?->info('✅ Old demo data cleaned.');

        // 5. Seed Transactions
        $this->seedTransactions($managedUsers);
        $this->command?->info('✅ Transactions seeded.');

        $this->command?->info('🎉 Comprehensive Sales Report seeding complete!');
        $this->command?->info('📊 Login as marketing@gmail.com / marketing123 to view /marketing/reports/sales');
    }

    /**
     * Create or retrieve the marketing user.
     */
    private function createMarketingUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'marketing@gmail.com'],
            [
                'name' => 'Marketing Manager',
                'username' => 'marketing',
                'role' => User::ROLE_MARKETING,
                'email_verified_at' => now(),
                'password' => Hash::make('marketing123'),
            ]
        );
    }

    /**
     * Create managed users under the marketing user.
     *
     * @param  User  $marketing
     * @return array<int, User>
     */
    private function createManagedUsers(User $marketing): array
    {
        $seeds = [
            [
                'email' => 'budi.santoso@rigel.com',
                'name' => 'Budi Santoso',
                'username' => 'budi.santoso',
                'phone' => '081234567801',
            ],
            [
                'email' => 'siti.aisyah@rigel.com',
                'name' => 'Siti Aisyah',
                'username' => 'siti.aisyah',
                'phone' => '081234567802',
            ],
            [
                'email' => 'raka.pratama@rigel.com',
                'name' => 'Raka Pratama',
                'username' => 'raka.pratama',
                'phone' => '081234567803',
            ],
            [
                'email' => 'nadia.putri@rigel.com',
                'name' => 'Nadia Putri',
                'username' => 'nadia.putri',
                'phone' => '081234567804',
            ],
            [
                'email' => 'fajar.hidayat@rigel.com',
                'name' => 'Fajar Hidayat',
                'username' => 'fajar.hidayat',
                'phone' => '081234567805',
            ],
            [
                'email' => 'dewi.sartika@rigel.com',
                'name' => 'Dewi Sartika',
                'username' => 'dewi.sartika',
                'phone' => '081234567806',
            ],
            [
                'email' => 'ahmad.fauzi@rigel.com',
                'name' => 'Ahmad Fauzi',
                'username' => 'ahmad.fauzi',
                'phone' => '081234567807',
            ],
            [
                'email' => 'rina.wulandari@rigel.com',
                'name' => 'Rina Wulandari',
                'username' => 'rina.wulandari',
                'phone' => '081234567808',
            ],
        ];

        $users = [];

        foreach ($seeds as $key => $seed) {
            // Check if user exists by username first (since it's unique)
            $user = User::where('username', $seed['username'])->first();
            
            if ($user) {
                // Update existing user's details if needed
                $updates = [];
                if ($user->email !== $seed['email']) {
                    $updates['email'] = $seed['email'];
                }
                if ($user->name !== $seed['name']) {
                    $updates['name'] = $seed['name'];
                }
                if ($user->marketing_owner_id !== $marketing->id) {
                    $updates['marketing_owner_id'] = $marketing->id;
                }
                
                if (!empty($updates)) {
                    $user->update($updates);
                }
                
                $this->command?->info("   ✓ User {$key}: {$user->name} (ID: {$user->id})");
            } else {
                // Create new user
                $user = User::create([
                    'email' => $seed['email'],
                    'name' => $seed['name'],
                    'username' => $seed['username'],
                    'role' => User::ROLE_USER,
                    'marketing_owner_id' => $marketing->id,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ]);
                $this->command?->info("   ✓ User {$key}: {$user->name} (ID: {$user->id}) [NEW]");
            }
            
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Ensure payment methods exist for the report.
     */
    private function ensurePaymentMethods(): void
    {
        // BCA Bank Account
        PaymentMethod::firstOrCreate(
            ['type' => 'bank_account', 'account_number' => '1234567890'],
            [
                'name' => 'BCA - Rigel Agency',
                'account_holder' => 'Rigel Agency',
                'is_active' => true,
            ]
        );

        // Mandiri Bank Account
        PaymentMethod::firstOrCreate(
            ['type' => 'bank_account', 'account_number' => '9876543210'],
            [
                'name' => 'Mandiri - Rigel Agency',
                'account_holder' => 'Rigel Agency',
                'is_active' => true,
            ]
        );

        // QRIS
        PaymentMethod::firstOrCreate(
            ['type' => 'qris', 'nmid' => 'ID1234567890123'],
            [
                'name' => 'QRIS Rigel Agency',
                'qr_code_path' => null,
                'is_active' => true,
            ]
        );
    }

    /**
     * Clean old demo transactions.
     */
    private function cleanOldData(): void
    {
        SaleTransaction::query()
            ->where('description', 'like', '[COMPREHENSIVE SALES REPORT]%')
            ->delete();
    }

    /**
     * Seed comprehensive transactions for sales report.
     *
     * @param  array<int, User>  $managedUsers
     */
    private function seedTransactions(array $managedUsers): void
    {
        $byEmail = collect($managedUsers)->keyBy('email');

        $pick = function (string $email) use ($byEmail): User {
            $user = $byEmail->get($email);
            if (! $user) {
                throw new \RuntimeException("User not found: {$email}");
            }
            return $user;
        };

        $now = now();
        $transactions = [];

        // ==========================================
        // TODAY'S TRANSACTIONS (April 14, 2026)
        // ==========================================
        $todayTransactions = [
            // Morning
            ['budi.santoso@rigel.com', 'Budi Santoso', 150_000, 5.0, 'success', 'topup', 'BCA', '08:15:00'],
            ['siti.aisyah@rigel.com', 'Siti Aisyah', 200_000, 4.5, 'success', 'topup', 'Mandiri', '09:30:00'],
            ['raka.pratama@rigel.com', 'Raka Pratama', 300_000, 5.0, 'success', 'topup', 'QRIS', '10:45:00'],
            
            // Midday
            ['nadia.putri@rigel.com', 'Nadia Putri', 175_000, 4.0, 'success', 'topup', 'BCA', '11:20:00'],
            ['fajar.hidayat@rigel.com', 'Fajar Hidayat', 450_000, 3.5, 'success', 'topup', 'BCA', '12:50:00'],
            ['dewi.sartika@rigel.com', 'Dewi Sartika', 125_000, 5.0, 'failed', 'topup', 'Mandiri', '13:15:00'],
            
            // Afternoon
            ['ahmad.fauzi@rigel.com', 'Ahmad Fauzi', 500_000, 3.0, 'success', 'topup', 'QRIS', '14:30:00'],
            ['rina.wulandari@rigel.com', 'Rina Wulandari', 225_000, 4.5, 'success', 'topup', 'BCA', '15:45:00'],
            ['budi.santoso@rigel.com', 'Budi Santoso', 350_000, 5.0, 'success', 'topup', 'BCA', '16:20:00'],
            
            // Evening
            ['siti.aisyah@rigel.com', 'Siti Aisyah', 275_000, 4.0, 'success', 'topup', 'Mandiri', '18:10:00'],
            ['raka.pratama@rigel.com', 'Raka Pratama', 180_000, 5.0, 'pending', 'topup', 'QRIS', '19:30:00'],
            ['nadia.putri@rigel.com', 'Nadia Putri', 600_000, 3.0, 'success', 'topup', 'BCA', '20:15:00'],
        ];

        foreach ($todayTransactions as $idx => $tx) {
            $transactions[] = [
                'user_email' => $tx[0],
                'customer_name' => $tx[1],
                'amount' => $tx[2],
                'commission_rate' => $tx[3],
                'status' => $tx[4],
                'transaction_type' => $tx[5],
                'payment_method' => $tx[6],
                'time' => $now->copy()->startOfDay()->setTimeFromTimeString($tx[7]),
                'suffix' => 'TODAY-' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
            ];
        }

        // ==========================================
        // THIS WEEK (April 7-13, 2026)
        // ==========================================
        $weekTransactions = [
            // April 7 (Monday)
            ['budi.santoso@rigel.com', 'Budi Santoso', 180_000, 5.0, 'success', '2026-04-07 09:00:00', 'APR07-01'],
            ['siti.aisyah@rigel.com', 'Siti Aisyah', 220_000, 4.5, 'success', '2026-04-07 14:30:00', 'APR07-02'],
            ['raka.pratama@rigel.com', 'Raka Pratama', 95_000, 5.0, 'failed', '2026-04-07 16:45:00', 'APR07-03'],
            
            // April 8 (Tuesday)
            ['nadia.putri@rigel.com', 'Nadia Putri', 310_000, 4.0, 'success', '2026-04-08 10:15:00', 'APR08-01'],
            ['fajar.hidayat@rigel.com', 'Fajar Hidayat', 275_000, 3.5, 'success', '2026-04-08 13:20:00', 'APR08-02'],
            
            // April 9 (Wednesday)
            ['dewi.sartika@rigel.com', 'Dewi Sartika', 420_000, 5.0, 'success', '2026-04-09 08:30:00', 'APR09-01'],
            ['ahmad.fauzi@rigel.com', 'Ahmad Fauzi', 150_000, 3.0, 'success', '2026-04-09 11:45:00', 'APR09-02'],
            ['rina.wulandari@rigel.com', 'Rina Wulandari', 380_000, 4.5, 'success', '2026-04-09 15:10:00', 'APR09-03'],
            
            // April 10 (Thursday)
            ['budi.santoso@rigel.com', 'Budi Santoso', 260_000, 5.0, 'success', '2026-04-10 09:25:00', 'APR10-01'],
            ['siti.aisyah@rigel.com', 'Siti Aisyah', 190_000, 4.0, 'failed', '2026-04-10 12:40:00', 'APR10-02'],
            
            // April 11 (Friday)
            ['raka.pratama@rigel.com', 'Raka Pratama', 340_000, 5.0, 'success', '2026-04-11 10:00:00', 'APR11-01'],
            ['nadia.putri@rigel.com', 'Nadia Putri', 225_000, 4.0, 'success', '2026-04-11 14:15:00', 'APR11-02'],
            ['fajar.hidayat@rigel.com', 'Fajar Hidayat', 510_000, 3.5, 'success', '2026-04-11 17:30:00', 'APR11-03'],
            
            // April 12 (Saturday)
            ['dewi.sartika@rigel.com', 'Dewi Sartika', 175_000, 5.0, 'success', '2026-04-12 11:20:00', 'APR12-01'],
            ['ahmad.fauzi@rigel.com', 'Ahmad Fauzi', 290_000, 3.0, 'success', '2026-04-12 15:45:00', 'APR12-02'],
            
            // April 13 (Sunday)
            ['rina.wulandari@rigel.com', 'Rina Wulandari', 410_000, 4.5, 'success', '2026-04-13 09:50:00', 'APR13-01'],
            ['budi.santoso@rigel.com', 'Budi Santoso', 200_000, 5.0, 'pending', '2026-04-13 13:30:00', 'APR13-02'],
        ];

        foreach ($weekTransactions as $tx) {
            $transactions[] = [
                'user_email' => $tx[0],
                'customer_name' => $tx[1],
                'amount' => $tx[2],
                'commission_rate' => $tx[3],
                'status' => $tx[4],
                'transaction_type' => 'topup',
                'payment_method' => ['BCA', 'Mandiri', 'QRIS'][array_rand(['BCA', 'Mandiri', 'QRIS'])],
                'time' => Carbon::parse($tx[5]),
                'suffix' => $tx[6],
            ];
        }

        // ==========================================
        // THIS MONTH (April 1-6, 2026)
        // ==========================================
        $monthTransactions = [
            ['budi.santoso@rigel.com', 'Budi Santoso', 145_000, 5.0, 'success', '2026-04-01 10:00:00', 'APR01-01'],
            ['siti.aisyah@rigel.com', 'Siti Aisyah', 235_000, 4.5, 'success', '2026-04-01 14:20:00', 'APR01-02'],
            
            ['raka.pratama@rigel.com', 'Raka Pratama', 320_000, 5.0, 'success', '2026-04-02 09:15:00', 'APR02-01'],
            ['nadia.putri@rigel.com', 'Nadia Putri', 185_000, 4.0, 'failed', '2026-04-02 16:30:00', 'APR02-02'],
            
            ['fajar.hidayat@rigel.com', 'Fajar Hidayat', 270_000, 3.5, 'success', '2026-04-03 11:45:00', 'APR03-01'],
            ['dewi.sartika@rigel.com', 'Dewi Sartika', 395_000, 5.0, 'success', '2026-04-03 15:10:00', 'APR03-02'],
            
            ['ahmad.fauzi@rigel.com', 'Ahmad Fauzi', 160_000, 3.0, 'success', '2026-04-04 08:30:00', 'APR04-01'],
            ['rina.wulandari@rigel.com', 'Rina Wulandari', 445_000, 4.5, 'success', '2026-04-04 13:50:00', 'APR04-02'],
            
            ['budi.santoso@rigel.com', 'Budi Santoso', 210_000, 5.0, 'success', '2026-04-05 10:20:00', 'APR05-01'],
            ['siti.aisyah@rigel.com', 'Siti Aisyah', 330_000, 4.0, 'success', '2026-04-05 14:40:00', 'APR05-02'],
            
            ['raka.pratama@rigel.com', 'Raka Pratama', 255_000, 5.0, 'success', '2026-04-06 09:00:00', 'APR06-01'],
            ['nadia.putri@rigel.com', 'Nadia Putri', 475_000, 3.5, 'success', '2026-04-06 12:15:00', 'APR06-02'],
            ['fajar.hidayat@rigel.com', 'Fajar Hidayat', 190_000, 3.0, 'failed', '2026-04-06 17:30:00', 'APR06-03'],
        ];

        foreach ($monthTransactions as $tx) {
            $transactions[] = [
                'user_email' => $tx[0],
                'customer_name' => $tx[1],
                'amount' => $tx[2],
                'commission_rate' => $tx[3],
                'status' => $tx[4],
                'transaction_type' => 'topup',
                'payment_method' => ['BCA', 'Mandiri', 'QRIS'][array_rand(['BCA', 'Mandiri', 'QRIS'])],
                'time' => Carbon::parse($tx[5]),
                'suffix' => $tx[6],
            ];
        }

        // ==========================================
        // LAST MONTH (March 2026) - for comparison
        // ==========================================
        $lastMonthTransactions = [
            ['budi.santoso@rigel.com', 'Budi Santoso', 175_000, 5.0, 'success', '2026-03-05 10:00:00', 'MAR05-01'],
            ['siti.aisyah@rigel.com', 'Siti Aisyah', 240_000, 4.5, 'success', '2026-03-08 14:30:00', 'MAR08-01'],
            ['raka.pratama@rigel.com', 'Raka Pratama', 310_000, 5.0, 'success', '2026-03-12 09:15:00', 'MAR12-01'],
            ['nadia.putri@rigel.com', 'Nadia Putri', 195_000, 4.0, 'success', '2026-03-15 11:45:00', 'MAR15-01'],
            ['fajar.hidayat@rigel.com', 'Fajar Hidayat', 425_000, 3.5, 'success', '2026-03-18 16:20:00', 'MAR18-01'],
            ['dewi.sartika@rigel.com', 'Dewi Sartika', 280_000, 5.0, 'failed', '2026-03-20 13:10:00', 'MAR20-01'],
            ['ahmad.fauzi@rigel.com', 'Ahmad Fauzi', 350_000, 3.0, 'success', '2026-03-22 10:30:00', 'MAR22-01'],
            ['rina.wulandari@rigel.com', 'Rina Wulandari', 460_000, 4.5, 'success', '2026-03-25 15:45:00', 'MAR25-01'],
            ['budi.santoso@rigel.com', 'Budi Santoso', 220_000, 5.0, 'success', '2026-03-27 09:20:00', 'MAR27-01'],
            ['siti.aisyah@rigel.com', 'Siti Aisyah', 385_000, 4.0, 'success', '2026-03-30 14:50:00', 'MAR30-01'],
        ];

        foreach ($lastMonthTransactions as $tx) {
            $transactions[] = [
                'user_email' => $tx[0],
                'customer_name' => $tx[1],
                'amount' => $tx[2],
                'commission_rate' => $tx[3],
                'status' => $tx[4],
                'transaction_type' => 'topup',
                'payment_method' => ['BCA', 'Mandiri'][array_rand(['BCA', 'Mandiri'])],
                'time' => Carbon::parse($tx[5]),
                'suffix' => $tx[6],
            ];
        }

        // ==========================================
        // CREATE ALL TRANSACTIONS
        // ==========================================
        $createdCount = 0;
        $successCount = 0;

        foreach ($transactions as $tx) {
            $user = $pick($tx['user_email']);
            $amount = $tx['amount'];
            $commissionRate = $tx['commission_rate'];
            $commissionAmount = round($amount * ($commissionRate / 100), 2);

            $transactionCode = 'SALES-' . $tx['suffix'] . '-' . strtoupper(substr(md5(uniqid()), 0, 6));

            $saleTransaction = SaleTransaction::create([
                'transaction_code' => $transactionCode,
                'user_id' => $user->id,
                'customer_name' => $tx['customer_name'],
                'customer_phone' => $user->phone ?? '081234567890',
                'amount' => $amount,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'status' => $tx['status'],
                'transaction_type' => $tx['transaction_type'],
                'description' => '[COMPREHENSIVE SALES REPORT] Demo transaction for ' . $tx['suffix'],
                'payment_method' => $tx['payment_method'],
                'payment_number' => 'DEMO' . rand(100000, 999999),
                'user_id_input' => (string) $user->id,
                'nickname' => $user->username ?? $user->name,
                'service_name' => 'Sales Report Demo Service',
                'created_at' => $tx['time'],
                'updated_at' => $tx['time'],
                'completed_at' => $tx['status'] === 'success' ? $tx['time'] : null,
            ]);

            $createdCount++;

            // Create commission for successful transactions
            if ($tx['status'] === 'success') {
                Commission::create([
                    'user_id' => $user->id,
                    'sale_transaction_id' => $saleTransaction->id,
                    'amount' => $commissionAmount,
                    'period_date' => $tx['time']->format('Y-m-d'),
                    'period_type' => 'daily',
                    'withdrawn' => false,
                ]);
                $successCount++;
            }
        }

        $this->command?->info("   📊 Total transactions created: {$createdCount}");
        $this->command?->info("   ✅ Successful transactions: {$successCount}");
        $this->command?->info("   ❌ Failed/Pending transactions: " . ($createdCount - $successCount));
    }
}
