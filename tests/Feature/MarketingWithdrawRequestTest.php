<?php

namespace Tests\Feature;

use App\Models\Commission;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MarketingWithdrawRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketing_can_request_withdrawal_up_to_available_commission(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $seedTransaction = SaleTransaction::factory()->create([
            'user_id' => $marketing->id,
            'status' => 'success',
            'transaction_type' => 'user_onboarding_bonus',
            'amount' => 1000,
            'commission_rate' => 100,
            'commission_amount' => 1000,
            'completed_at' => now(),
        ]);

        Commission::create([
            'user_id' => $marketing->id,
            'sale_transaction_id' => $seedTransaction->id,
            'amount' => 1000,
            'period_date' => Carbon::today(),
            'period_type' => 'daily',
            'withdrawn' => false,
        ]);

        $this->get(route('marketing.withdraw.index'))
            ->assertOk()
            ->assertViewHas('availableCommission', 1000.0);

        $response = $this->postJson(route('marketing.withdraw.request'), [
            'account_name' => 'John Doe',
            'bank_name' => 'BCA',
            'account_number' => '123',
            'whatsapp_number' => '0812',
            'address' => 'Jakarta',
            'amount' => 600,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'remaining_balance' => 400.0,
            ]);

        $withdrawal = SaleTransaction::query()
            ->where('user_id', $marketing->id)
            ->where('transaction_type', 'withdrawal')
            ->latest('id')
            ->first();

        $this->assertNotNull($withdrawal);
        $this->assertSame('pending', $withdrawal->status);
        $this->assertSame(600.0, (float) $withdrawal->amount);

        $this->assertSame(400.0, (float) Commission::query()->where('user_id', $marketing->id)->where('withdrawn', false)->sum('amount'));
        $this->assertSame(600.0, (float) Commission::query()->where('user_id', $marketing->id)->where('withdrawn', true)->sum('amount'));
    }

    public function test_marketing_cannot_request_withdrawal_over_balance(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $seedTransaction = SaleTransaction::factory()->create([
            'user_id' => $marketing->id,
            'status' => 'success',
            'transaction_type' => 'user_onboarding_bonus',
            'amount' => 500,
            'commission_rate' => 100,
            'commission_amount' => 500,
            'completed_at' => now(),
        ]);

        Commission::create([
            'user_id' => $marketing->id,
            'sale_transaction_id' => $seedTransaction->id,
            'amount' => 500,
            'period_date' => Carbon::today(),
            'period_type' => 'daily',
            'withdrawn' => false,
        ]);

        $this->postJson(route('marketing.withdraw.request'), [
            'account_name' => 'John Doe',
            'bank_name' => 'BCA',
            'account_number' => '123',
            'amount' => 600,
        ])->assertStatus(400);
    }
}

