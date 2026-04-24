<?php

namespace Tests\Feature;

use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MarketingOwnershipScopeModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketing_modules_are_scoped_to_owned_users_and_self_transactions(): void
    {
        $marketingA = User::factory()->create(['role' => 'marketing']);
        $marketingB = User::factory()->create(['role' => 'marketing']);

        $userA = User::factory()->create(['role' => 'user', 'marketing_owner_id' => $marketingA->id]);
        $userB = User::factory()->create(['role' => 'user', 'marketing_owner_id' => $marketingB->id]);

        $now = Carbon::parse('2026-04-13 10:00:00');

        $inScopeUser = SaleTransaction::factory()->create([
            'user_id' => $userA->id,
            'customer_name' => 'IN_SCOPE_USER',
            'transaction_type' => 'topup',
            'status' => 'success',
            'amount' => 100,
            'commission_amount' => 1,
            'created_at' => $now,
        ]);

        $inScopeSelf = SaleTransaction::factory()->create([
            'user_id' => $marketingA->id,
            'customer_name' => 'IN_SCOPE_SELF',
            'transaction_type' => 'topup',
            'status' => 'success',
            'amount' => 300,
            'commission_amount' => 3,
            'created_at' => $now,
        ]);

        $outOfScope = SaleTransaction::factory()->create([
            'user_id' => $userB->id,
            'customer_name' => 'OUT_SCOPE',
            'transaction_type' => 'topup',
            'status' => 'success',
            'amount' => 200,
            'commission_amount' => 2,
            'created_at' => $now,
        ]);

        $this->actingAs($marketingA);

        $this->get(route('marketing.sales.index'))
            ->assertOk()
            ->assertViewHas('sales', function ($paginator) use ($inScopeUser, $inScopeSelf, $outOfScope) {
                $ids = $paginator->getCollection()->pluck('id');

                return $ids->contains($inScopeUser->id)
                    && $ids->contains($inScopeSelf->id)
                    && ! $ids->contains($outOfScope->id);
            });

        $this->get(route('marketing.transactions.index'))
            ->assertOk()
            ->assertViewHas('transactions', function ($paginator) use ($inScopeUser, $inScopeSelf, $outOfScope) {
                $ids = $paginator->getCollection()->pluck('id');

                return $ids->contains($inScopeUser->id)
                    && $ids->contains($inScopeSelf->id)
                    && ! $ids->contains($outOfScope->id);
            });

        $this->get(route('marketing.wallet.index'))
            ->assertOk()
            ->assertViewHas('totalBalance', function ($totalBalance) {
                return (float) $totalBalance === 400.0;
            });

        $reportResponse = $this->get(route('marketing.reports.sales', [
            'period' => 'daily',
            'date' => '2026-04-13',
        ]));

        $reportResponse->assertOk();
        $reportResponse->assertViewHas('summary', function ($summary) {
            return (float) ($summary->total_sales ?? 0) === 400.0;
        });

        $this->post(route('marketing.wallet.approve', ['id' => $outOfScope->id]))->assertForbidden();
        $this->get(route('marketing.transactions.show', $outOfScope))->assertForbidden();
    }
}

