<?php

namespace Tests\Feature;

use App\Models\Commission;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TransactionsWithdrawalRefundTest extends TestCase
{
    use RefreshDatabase;

    public function test_transactions_update_status_failed_restores_withdrawn_commissions_for_withdrawal(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $marketing = User::factory()->create(['role' => 'marketing']);

        $withdrawal = SaleTransaction::factory()->create([
            'user_id' => $marketing->id,
            'transaction_type' => 'withdrawal',
            'status' => 'pending',
            'amount' => 600,
        ]);

        $otherWithdrawal = SaleTransaction::factory()->create([
            'user_id' => $marketing->id,
            'transaction_type' => 'withdrawal',
            'status' => 'success',
            'amount' => 400,
        ]);

        $commission = Commission::create([
            'user_id' => $marketing->id,
            'sale_transaction_id' => $withdrawal->id,
            'amount' => 600,
            'period_date' => Carbon::today(),
            'period_type' => 'daily',
            'withdrawn' => true,
            'withdrawal_transaction_id' => $withdrawal->id,
        ]);

        $commissionOther = Commission::create([
            'user_id' => $marketing->id,
            'sale_transaction_id' => $otherWithdrawal->id,
            'amount' => 400,
            'period_date' => Carbon::today(),
            'period_type' => 'daily',
            'withdrawn' => true,
            'withdrawal_transaction_id' => $otherWithdrawal->id,
        ]);

        $this->actingAs($admin);

        $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->put(route('transactions.updateStatus', ['transaction' => $withdrawal->id]), [
                'status' => 'failed',
            ])
            ->assertOk()
            ->assertJson([
                'message' => 'Status transaksi berhasil diperbarui.',
            ]);

        $this->assertSame('failed', $withdrawal->fresh()->status);

        // Only the commissions tagged to this withdrawal are restored.
        $this->assertSame(600.0, (float) Commission::query()->where('user_id', $marketing->id)->where('withdrawn', false)->sum('amount'));
        $this->assertSame(400.0, (float) Commission::query()->where('user_id', $marketing->id)->where('withdrawn', true)->sum('amount'));

        $commission->refresh();
        $this->assertSame(600.0, (float) $commission->amount);
        $this->assertFalse((bool) $commission->withdrawn);
        $this->assertNull($commission->withdrawal_transaction_id);

        $commissionOther->refresh();
        $this->assertSame(400.0, (float) $commissionOther->amount);
        $this->assertTrue((bool) $commissionOther->withdrawn);
        $this->assertSame($otherWithdrawal->id, $commissionOther->withdrawal_transaction_id);
    }

    public function test_withdrawal_status_is_final_after_failed_via_transactions_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $marketing = User::factory()->create(['role' => 'marketing']);

        $withdrawal = SaleTransaction::factory()->create([
            'user_id' => $marketing->id,
            'transaction_type' => 'withdrawal',
            'status' => 'failed',
            'amount' => 600,
        ]);

        $this->actingAs($admin);

        $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->put(route('transactions.updateStatus', ['transaction' => $withdrawal->id]), [
                'status' => 'process',
            ])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Status penarikan sudah final dan tidak bisa diubah lagi.',
            ]);

        $this->assertSame('failed', $withdrawal->fresh()->status);
    }
}
