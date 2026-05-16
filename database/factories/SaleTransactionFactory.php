<?php

namespace Database\Factories;

use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleTransaction>
 */
class SaleTransactionFactory extends Factory
{
    protected $model = SaleTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'amount' => fake()->randomElement([100000, 250000, 500000, 1000000, 2500000]),
            'commission_rate' => 1.00,
            'commission_amount' => function (array $attributes) {
                return $attributes['amount'] * 0.01; // 1% commission
            },
            'status' => fake()->randomElement(['process', 'success', 'failed']),
            'user_id' => User::factory(),
            'confirmed_at' => fake()->optional()->dateTime(),
            'completed_at' => fake()->optional()->dateTime(),
        ];
    }
}