<?php

namespace Tests\Feature;

use App\Models\Commission;
use App\Models\SaleTransaction;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingBonusSettingOverrideTest extends TestCase
{
    use RefreshDatabase;

    public function test_bonus_amount_can_be_overridden_by_settings_table(): void
    {
        Setting::set('marketing.user_create_bonus_mode', 'nominal', 'text', 'marketing');
        Setting::set('marketing.user_create_bonus_amount', 7777, 'number', 'marketing');

        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $this->post(route('marketing.users.store'), [
            'name' => 'Owned User',
            'email' => 'owned-setting@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('marketing.users.index'));

        $bonusTransaction = SaleTransaction::query()
            ->where('user_id', $marketing->id)
            ->where('transaction_type', 'user_onboarding_bonus')
            ->first();

        $this->assertNotNull($bonusTransaction);
        $this->assertSame(7777.0, (float) $bonusTransaction->amount);

        $commission = Commission::query()
            ->where('user_id', $marketing->id)
            ->where('sale_transaction_id', $bonusTransaction->id)
            ->first();

        $this->assertNotNull($commission);
        $this->assertSame(7777.0, (float) $commission->amount);
    }
}
