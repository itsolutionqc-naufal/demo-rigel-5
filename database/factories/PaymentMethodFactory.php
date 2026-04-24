<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['bank_account', 'qris']);
        
        return [
            'name' => fake()->company() . ' ' . ucfirst($type),
            'type' => $type,
            'account_number' => $type === 'bank_account' ? fake()->bankAccountNumber() : null,
            'account_holder' => $type === 'bank_account' ? fake()->name() : null,
            'qr_code_path' => $type === 'qris' ? 'qris/' . fake()->uuid() . '.png' : null,
            'is_active' => true,
        ];
    }
}