<?php

namespace Database\Seeders;

use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionStatusManualTestSeeder extends Seeder
{
    /**
     * Seed 2 pending transactions for manual status update testing.
     */
    public function run(): void
    {
        $targetUser = User::query()
            ->where('role', User::ROLE_USER)
            ->orderBy('id')
            ->first() ?? User::query()->orderBy('id')->first();

        if (! $targetUser) {
            $this->command?->warn('Seeder skipped: no users found.');
            return;
        }

        $rows = [
            [
                'transaction_code' => 'TRX-MANUAL-TEST-001',
                'customer_name' => 'Manual Test User 1',
                'customer_phone' => '081200000001',
                'amount' => 80000,
                'commission_rate' => 5,
                'commission_amount' => 4000,
                'status' => 'pending',
                'transaction_type' => 'topup',
                'description' => 'Manual status test transaction #1',
                'payment_method' => 'BCA',
                'payment_number' => '123450001',
                'user_id_input' => 'ML-TEST-001',
                'nickname' => 'ManualTester1',
                'service_name' => 'Mobile Legends',
                'completed_at' => null,
            ],
            [
                'transaction_code' => 'TRX-MANUAL-TEST-002',
                'customer_name' => 'Manual Test User 2',
                'customer_phone' => '081200000002',
                'amount' => 120000,
                'commission_rate' => 4,
                'commission_amount' => 4800,
                'status' => 'pending',
                'transaction_type' => 'topup',
                'description' => 'Manual status test transaction #2',
                'payment_method' => 'QRIS',
                'payment_number' => 'QRIS-TEST-002',
                'user_id_input' => 'FF-TEST-002',
                'nickname' => 'ManualTester2',
                'service_name' => 'Free Fire',
                'completed_at' => null,
            ],
            [
                'transaction_code' => 'TRX-MANUAL-TEST-003',
                'customer_name' => 'Manual Test User 3',
                'customer_phone' => '081200000003',
                'amount' => 95000,
                'commission_rate' => 5,
                'commission_amount' => 4750,
                'status' => 'pending',
                'transaction_type' => 'topup',
                'description' => 'Manual status test transaction #3',
                'payment_method' => 'BRI',
                'payment_number' => '998870003',
                'user_id_input' => 'PUBG-TEST-003',
                'nickname' => 'ManualTester3',
                'service_name' => 'PUBG Mobile',
                'completed_at' => null,
            ],
            [
                'transaction_code' => 'TRX-MANUAL-TEST-004',
                'customer_name' => 'Manual Test User 4',
                'customer_phone' => '081200000004',
                'amount' => 110000,
                'commission_rate' => 4,
                'commission_amount' => 4400,
                'status' => 'pending',
                'transaction_type' => 'topup',
                'description' => 'Manual status test transaction #4',
                'payment_method' => 'DANA',
                'payment_number' => 'DANA-TEST-004',
                'user_id_input' => 'HOK-TEST-004',
                'nickname' => 'ManualTester4',
                'service_name' => 'Honor of Kings',
                'completed_at' => null,
            ],
            [
                'transaction_code' => 'TRX-MANUAL-TEST-005',
                'customer_name' => 'Manual Test User 5',
                'customer_phone' => '081200000005',
                'amount' => 70000,
                'commission_rate' => 6,
                'commission_amount' => 4200,
                'status' => 'pending',
                'transaction_type' => 'topup',
                'description' => 'Manual status test transaction #5',
                'payment_method' => 'OVO',
                'payment_number' => 'OVO-TEST-005',
                'user_id_input' => 'VALO-TEST-005',
                'nickname' => 'ManualTester5',
                'service_name' => 'Valorant',
                'completed_at' => null,
            ],
            [
                'transaction_code' => 'TRX-MANUAL-TEST-006',
                'customer_name' => 'Manual Test User 6',
                'customer_phone' => '081200000006',
                'amount' => 135000,
                'commission_rate' => 5,
                'commission_amount' => 6750,
                'status' => 'pending',
                'transaction_type' => 'topup',
                'description' => 'Manual status test transaction #6',
                'payment_method' => 'BNI',
                'payment_number' => 'BNI-TEST-006',
                'user_id_input' => 'CODM-TEST-006',
                'nickname' => 'ManualTester6',
                'service_name' => 'Call of Duty Mobile',
                'completed_at' => null,
            ],
            [
                'transaction_code' => 'TRX-MANUAL-TEST-007',
                'customer_name' => 'Manual Test User 7',
                'customer_phone' => '081200000007',
                'amount' => 88000,
                'commission_rate' => 4,
                'commission_amount' => 3520,
                'status' => 'pending',
                'transaction_type' => 'topup',
                'description' => 'Manual status test transaction #7',
                'payment_method' => 'ShopeePay',
                'payment_number' => 'SPAY-TEST-007',
                'user_id_input' => 'AOV-TEST-007',
                'nickname' => 'ManualTester7',
                'service_name' => 'Arena of Valor',
                'completed_at' => null,
            ],
        ];

        foreach ($rows as $payload) {
            SaleTransaction::query()->updateOrCreate(
                ['transaction_code' => $payload['transaction_code']],
                ['user_id' => $targetUser->id] + $payload
            );
        }
    }
}
