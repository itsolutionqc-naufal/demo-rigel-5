<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SaleTransaction;
use App\Models\Commission;
use Illuminate\Database\Seeder;

class WithdrawalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'user@gmail.com')->first();

        if (!$user) {
            $this->command->error('User not found!');
            return;
        }

        // Create some withdrawal transactions with different statuses
        $withdrawals = [
            [
                'amount' => 20000,
                'status' => 'success',
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'Regular User',
                'whatsapp_number' => '081234567890',
                'description' => 'Penarikan ke BCA - 1234567890',
                'created_at' => now()->subDays(5),
            ],
            [
                'amount' => 15000,
                'status' => 'pending',
                'bank_name' => 'Mandiri',
                'account_number' => '9876543210',
                'account_name' => 'Regular User',
                'whatsapp_number' => '081234567890',
                'description' => 'Penarikan ke Mandiri - 9876543210',
                'created_at' => now()->subDays(2),
            ],
            [
                'amount' => 10000,
                'status' => 'process',
                'bank_name' => 'Dana',
                'account_number' => '081234567890',
                'account_name' => 'Regular User',
                'whatsapp_number' => '081234567890',
                'description' => 'Penarikan ke Dana - 081234567890',
                'created_at' => now()->subDays(1),
            ],
            [
                'amount' => 5000,
                'status' => 'failed',
                'bank_name' => 'Gopay',
                'account_number' => '081234567890',
                'account_name' => 'Regular User',
                'whatsapp_number' => '081234567890',
                'description' => 'Penarikan ke Gopay - 081234567890',
                'created_at' => now()->subDays(3),
            ],
        ];

        foreach ($withdrawals as $withdrawal) {
            SaleTransaction::create([
                'user_id' => $user->id,
                'amount' => $withdrawal['amount'],
                'status' => $withdrawal['status'],
                'transaction_type' => 'withdrawal',
                'description' => $withdrawal['description'],
                'payment_method' => $withdrawal['bank_name'],
                'bank_name' => $withdrawal['bank_name'],
                'account_number' => $withdrawal['account_number'],
                'account_name' => $withdrawal['account_name'],
                'whatsapp_number' => $withdrawal['whatsapp_number'],
                'created_at' => $withdrawal['created_at'],
                'updated_at' => $withdrawal['created_at'],
            ]);
        }

        // Mark some commissions as withdrawn for the successful withdrawal
        $withdrawnAmount = 20000;
        $commissions = Commission::where('user_id', $user->id)
            ->where('withdrawn', false)
            ->orderBy('created_at', 'asc')
            ->get();

        $remaining = $withdrawnAmount;
        foreach ($commissions as $commission) {
            if ($remaining <= 0) {
                break;
            }

            if ($commission->amount <= $remaining) {
                $commission->update(['withdrawn' => true]);
                $remaining -= $commission->amount;
            }
        }

        $this->command->info('Withdrawal transactions seeded successfully!');
    }
}
