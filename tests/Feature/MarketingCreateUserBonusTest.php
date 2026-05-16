<?php

namespace Tests\Feature;

use App\Models\Commission;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingCreateUserBonusTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketing_gets_bonus_commission_after_creating_user(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);

        $this->actingAs($marketing);

        $this->post(route('marketing.users.store'), [
            'name' => 'Owned User',
            'email' => 'owned-bonus@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('marketing.users.index'));

        $bonusAmount = (int) config('marketing.user_create_bonus_amount', 1000);

        $bonusTransaction = SaleTransaction::query()
            ->where('user_id', $marketing->id)
            ->where('transaction_type', 'user_onboarding_bonus')
            ->first();

        $this->assertNotNull($bonusTransaction);
        $this->assertSame('success', $bonusTransaction->status);
        $this->assertSame((float) $bonusAmount, (float) $bonusTransaction->amount);

        $commission = Commission::query()
            ->where('user_id', $marketing->id)
            ->where('sale_transaction_id', $bonusTransaction->id)
            ->first();

        $this->assertNotNull($commission);
        $this->assertSame((float) $bonusAmount, (float) $commission->amount);
        $this->assertFalse((bool) $commission->withdrawn);
    }
}

