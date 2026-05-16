<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Commission;
use Illuminate\Support\Facades\Hash;

class UserBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create user with email user@gmail.com
        $user = User::where('email', 'user@gmail.com')->first();

        if (!$user) {
            echo "User with email user@gmail.com not found. Please run UserSeeder first.\n";
            return;
        }

        // Delete existing commissions for this user to avoid duplicates
        Commission::where('user_id', $user->id)->delete();
        \App\Models\SaleTransaction::where('user_id', $user->id)->delete();

        // Create multiple sale transactions to generate 500,000 commission total
        
        // Transaction 1: 20,000,000 dengan 1% komisi = 200,000
        $saleTransaction1 = \App\Models\SaleTransaction::create([
            'user_id' => $user->id,
            'customer_name' => 'Customer Mobile Legends',
            'customer_phone' => '081234567890',
            'amount' => 20000000,
            'commission_rate' => 1.00,
            'commission_amount' => 200000,
            'status' => 'success',
            'transaction_type' => 'topup',
            'service_name' => 'Mobile Legends',
            'user_id_input' => '123456789',
            'nickname' => 'ProGamer',
            'payment_method' => 'Dana',
            'payment_number' => '081234567890',
            'confirmed_at' => now(),
            'completed_at' => now(),
        ]);

        Commission::create([
            'user_id' => $user->id,
            'sale_transaction_id' => $saleTransaction1->id,
            'amount' => 200000,
            'period_date' => now()->subDays(5)->toDateString(),
            'period_type' => 'daily',
            'withdrawn' => false,
        ]);

        // Transaction 2: 15,000,000 dengan 1% komisi = 150,000
        $saleTransaction2 = \App\Models\SaleTransaction::create([
            'user_id' => $user->id,
            'customer_name' => 'Customer Free Fire',
            'customer_phone' => '081234567891',
            'amount' => 15000000,
            'commission_rate' => 1.00,
            'commission_amount' => 150000,
            'status' => 'success',
            'transaction_type' => 'topup',
            'service_name' => 'Free Fire',
            'user_id_input' => '987654321',
            'nickname' => 'FFMaster',
            'payment_method' => 'Gopay',
            'payment_number' => '081234567891',
            'confirmed_at' => now(),
            'completed_at' => now(),
        ]);

        Commission::create([
            'user_id' => $user->id,
            'sale_transaction_id' => $saleTransaction2->id,
            'amount' => 150000,
            'period_date' => now()->subDays(3)->toDateString(),
            'period_type' => 'daily',
            'withdrawn' => false,
        ]);

        // Transaction 3: 15,000,000 dengan 1% komisi = 150,000
        $saleTransaction3 = \App\Models\SaleTransaction::create([
            'user_id' => $user->id,
            'customer_name' => 'Customer PUBG',
            'customer_phone' => '081234567892',
            'amount' => 15000000,
            'commission_rate' => 1.00,
            'commission_amount' => 150000,
            'status' => 'success',
            'transaction_type' => 'topup',
            'service_name' => 'PUBG Mobile',
            'user_id_input' => '555666777',
            'nickname' => 'PUBGPro',
            'payment_method' => 'BCA',
            'payment_number' => '1234567890',
            'confirmed_at' => now(),
            'completed_at' => now(),
        ]);

        Commission::create([
            'user_id' => $user->id,
            'sale_transaction_id' => $saleTransaction3->id,
            'amount' => 150000,
            'period_date' => now()->subDays(1)->toDateString(),
            'period_type' => 'daily',
            'withdrawn' => false,
        ]);

        echo "✅ Successfully created 500,000 commission balance for user@gmail.com\n";
        echo "   - Transaction 1: Rp 200,000 (Mobile Legends - 1% dari Rp 20.000.000)\n";
        echo "   - Transaction 2: Rp 150,000 (Free Fire - 1% dari Rp 15.000.000)\n";
        echo "   - Transaction 3: Rp 150,000 (PUBG Mobile - 1% dari Rp 15.000.000)\n";
        echo "   - Total Balance: Rp 500,000\n";
    }
}
