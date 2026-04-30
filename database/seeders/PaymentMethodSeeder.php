<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample bank accounts
        PaymentMethod::factory()->create([
            'name' => 'BCA - Rigel Agency',
            'type' => 'bank_account',
            'account_number' => '1234567890',
            'account_holder' => 'Rigel Agency',
            'is_active' => true,
        ]);

        PaymentMethod::factory()->create([
            'name' => 'Mandiri - Rigel Agency',
            'type' => 'bank_account',
            'account_number' => '0987654321',
            'account_holder' => 'Rigel Agency',
            'is_active' => true,
        ]);

        // Create sample QRIS
        PaymentMethod::factory()->create([
            'name' => 'QRIS Rigel Agency',
            'type' => 'qris',
            'qr_code_path' => null, // No actual file, will show placeholder
            'nmid' => 'ID1234567890123',
            'is_active' => true,
        ]);
    }
}