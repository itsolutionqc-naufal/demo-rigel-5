<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Commission;
use App\Models\SaleTransaction;
use Illuminate\Support\Facades\Hash;

class UserBalanceUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the user with email 'user@gmail.com'
        $user = User::where('email', 'user@gmail.com')->first();

        if (!$user) {
            // If user doesn't exist, create one
            $user = User::create([
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]);
        }

        // Create sale transactions and corresponding commissions to reach 850,000 total
        $totalAmount = 850000;
        $transactionAmount = 1000000; // Amount for the transaction
        $commissionRate = 85.00; // Percentage to get 850,000 commission from 1M transaction
        $commissionAmount = $transactionAmount * ($commissionRate / 100);

        // Create a sale transaction
        $saleTransaction = SaleTransaction::create([
            'user_id' => $user->id,
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'amount' => $transactionAmount,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'status' => 'success',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create commission record for the user
        Commission::create([
            'user_id' => $user->id,
            'sale_transaction_id' => $saleTransaction->id,
            'amount' => $commissionAmount, // 850,000
            'period_date' => now()->toDateString(),
            'period_type' => 'daily',
            'withdrawn' => false, // Not withdrawn, so it contributes to the balance
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
