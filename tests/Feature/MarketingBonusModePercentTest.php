<?php

namespace Tests\Feature;

use App\Models\SaleTransaction;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingBonusModePercentTest extends TestCase
{
    use RefreshDatabase;

    public function test_bonus_amount_uses_percent_mode(): void
    {
        Setting::set('marketing.user_create_bonus_mode', 'percent', 'text', 'marketing');
        Setting::set('marketing.user_create_bonus_percent', 25, 'number', 'marketing');

        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $this->post(route('marketing.users.store'), [
            'name' => 'Owned User',
            'email' => 'owned-percent@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('marketing.users.index'));

        $base = (int) config('marketing.user_create_bonus_amount', 1000);
        $expected = (int) round($base * 0.25);

        $bonusTransaction = SaleTransaction::query()
            ->where('user_id', $marketing->id)
            ->where('transaction_type', 'user_onboarding_bonus')
            ->first();

        $this->assertNotNull($bonusTransaction);
        $this->assertSame((float) $expected, (float) $bonusTransaction->amount);
    }
}

