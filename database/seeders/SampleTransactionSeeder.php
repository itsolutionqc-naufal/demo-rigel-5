<?php

namespace Database\Seeders;

use App\Models\SaleTransaction;
use Illuminate\Database\Seeder;

class SampleTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample topup transactions with user_id_input and nickname
        SaleTransaction::create([
            'user_id' => 1,
            'customer_name' => 'Test User',
            'customer_phone' => '081234567890',
            'amount' => 50000,
            'commission_rate' => 5,
            'commission_amount' => 2500,
            'status' => 'pending',
            'transaction_type' => 'topup',
            'description' => 'Top Up Mobile Legends - ID: 123456789 - Nickname: ProGamer123',
            'payment_method' => 'BCA',
            'payment_number' => '1234567890',
            'user_id_input' => '123456789',
            'nickname' => 'ProGamer123',
            'service_name' => 'Mobile Legends'
        ]);

        SaleTransaction::create([
            'user_id' => 2,
            'customer_name' => 'Jane Smith',
            'customer_phone' => '081555666777',
            'amount' => 75000,
            'commission_rate' => 3,
            'commission_amount' => 2250,
            'status' => 'pending',
            'transaction_type' => 'topup',
            'description' => 'Top Up Free Fire - ID: 555666777 - Nickname: FireQueen',
            'payment_method' => 'QRIS',
            'payment_number' => 'QRIS Payment',
            'user_id_input' => '555666777',
            'nickname' => 'FireQueen',
            'service_name' => 'Free Fire'
        ]);

        // Create sample withdrawal transaction
        SaleTransaction::create([
            'user_id' => 1,
            'customer_name' => 'John Doe',
            'customer_phone' => '081987654321',
            'amount' => 100000,
            'commission_rate' => 0,
            'commission_amount' => 0,
            'status' => 'pending',
            'transaction_type' => 'withdrawal',
            'description' => 'Withdrawal Request - ID: 987654321 - Nickname: JohnGamer',
            'payment_method' => 'Mandiri',
            'payment_number' => '0987654321',
            'user_id_input' => '987654321',
            'nickname' => 'JohnGamer',
            'service_name' => 'Penarikan Saldo'
        ]);

        // Create sample with success status
        SaleTransaction::create([
            'user_id' => 2,
            'customer_name' => 'Alice Wonder',
            'customer_phone' => '081777888999',
            'amount' => 25000,
            'commission_rate' => 4,
            'commission_amount' => 1000,
            'status' => 'success',
            'transaction_type' => 'topup',
            'description' => 'Top Up PUBG Mobile - ID: 777888999 - Nickname: AliceWonder',
            'payment_method' => 'BRI',
            'payment_number' => '4567890123',
            'user_id_input' => '777888999',
            'nickname' => 'AliceWonder',
            'service_name' => 'PUBG Mobile',
            'completed_at' => now()
        ]);
    }
}