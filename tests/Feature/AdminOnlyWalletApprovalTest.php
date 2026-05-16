<?php

namespace Tests\Feature;

use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOnlyWalletApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketing_cannot_approve_or_reject_wallet_transactions(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $transaction = SaleTransaction::factory()->create([
            'status' => 'pending',
            'transaction_type' => 'topup',
        ]);

        $this->actingAs($marketing);

        $this->post(route('marketing.wallet.approve', ['id' => $transaction->id]))->assertForbidden();
        $this->post(route('marketing.wallet.reject', ['id' => $transaction->id]))->assertForbidden();
    }

    public function test_admin_can_approve_wallet_transactions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $transaction = SaleTransaction::factory()->create([
            'status' => 'pending',
            'transaction_type' => 'topup',
        ]);

        $this->actingAs($admin);

        $this->post(route('wallet.approve', ['id' => $transaction->id]))
            ->assertRedirect();

        $this->assertSame('success', $transaction->fresh()->status);
    }
}

