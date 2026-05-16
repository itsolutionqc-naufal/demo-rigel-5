<?php

namespace Database\Factories;

use App\Models\Commission;
use App\Models\User;
use App\Models\SaleTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commission>
 */
class CommissionFactory extends Factory
{
    protected $model = Commission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'sale_transaction_id' => SaleTransaction::factory(),
            'amount' => fake()->randomElement([1000, 2500, 5000, 10000, 25000]),
            'period_date' => fake()->date(),
            'period_type' => fake()->randomElement(['daily', 'monthly']),
            'withdrawn' => false,
            'withdrawal_transaction_id' => null,
        ];
    }
}
