<?php

use App\Models\Commission;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('allows marketing to request withdrawal and admin to approve it', function () {
    $marketing = User::factory()->create(['role' => User::ROLE_MARKETING]);

    $seedTransaction = SaleTransaction::factory()->create([
        'user_id' => $marketing->id,
        'status' => 'success',
        'transaction_type' => 'user_onboarding_bonus',
        'amount' => 1000,
        'commission_rate' => 100,
        'commission_amount' => 1000,
        'completed_at' => now(),
    ]);

    Commission::factory()->create([
        'user_id' => $marketing->id,
        'sale_transaction_id' => $seedTransaction->id,
        'amount' => 1000,
        'period_date' => Carbon::today(),
        'period_type' => 'daily',
        'withdrawn' => false,
    ]);

    $this->actingAs($marketing)
        ->get(route('marketing.withdraw.index'))
        ->assertOk()
        ->assertViewHas('availableCommission', 1000.0);

    $this->actingAs($marketing)
        ->postJson(route('marketing.withdraw.request'), [
            'account_name' => 'John Doe',
            'bank_name' => 'BCA',
            'account_number' => '123',
            'whatsapp_number' => '0812',
            'address' => 'Jakarta',
            'amount' => 600,
        ])
        ->assertOk()
        ->assertJson([
            'success' => true,
            'remaining_balance' => 400.0,
        ]);

    $withdrawal = SaleTransaction::query()
        ->where('user_id', $marketing->id)
        ->where('transaction_type', 'withdrawal')
        ->latest('id')
        ->first();

    expect($withdrawal)->not->toBeNull();
    expect($withdrawal->status)->toBe('pending');
    expect((float) $withdrawal->amount)->toBe(600.0);
    expect($withdrawal->bank_name)->toBe('BCA');
    expect($withdrawal->account_number)->toBe('123');
    expect($withdrawal->account_name)->toBe('John Doe');

    expect((float) Commission::query()->where('user_id', $marketing->id)->where('withdrawn', false)->sum('amount'))
        ->toBe(400.0);
    expect((float) Commission::query()->where('user_id', $marketing->id)->where('withdrawn', true)->sum('amount'))
        ->toBe(600.0);

    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->post(route('wallet.approve', ['id' => $withdrawal->id]))
        ->assertRedirect();

    expect($withdrawal->fresh()->status)->toBe('success');

    // Approval does not refund commissions; they stay marked as withdrawn.
    expect((float) Commission::query()->where('user_id', $marketing->id)->where('withdrawn', false)->sum('amount'))
        ->toBe(400.0);
    expect((float) Commission::query()->where('user_id', $marketing->id)->where('withdrawn', true)->sum('amount'))
        ->toBe(600.0);
});
