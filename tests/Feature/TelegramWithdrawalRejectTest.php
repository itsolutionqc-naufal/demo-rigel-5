<?php

namespace Tests\Feature;

use App\Models\Commission;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TelegramWithdrawalRejectTest extends TestCase
{
    use RefreshDatabase;

    public function test_telegram_reject_withdrawal_restores_withdrawn_commissions(): void
    {
        config()->set('services.telegram.bot_token', null);
        config()->set('services.telegram.admin_chat_id', null);

        $marketing = User::factory()->create(['role' => 'marketing']);

        $withdrawal = SaleTransaction::factory()->create([
            'user_id' => $marketing->id,
            'transaction_type' => 'withdrawal',
            'status' => 'pending',
            'amount' => 600,
            'transaction_code' => 'WD-TEST123',
            'bank_name' => 'BCA',
            'account_number' => '123',
            'account_name' => 'John Doe',
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

        $payload = [
            'callback_query' => [
                'id' => 'cbq-1',
                'data' => 'reject|default|WD-TEST123',
                'from' => [
                    'id' => 1,
                    'first_name' => 'Admin',
                ],
                'message' => [
                    'message_id' => 10,
                    'chat' => ['id' => 123],
                    'from' => ['id' => 999],
                ],
            ],
        ];

        $secret = env('TELEGRAM_WEBHOOK_SECRET');
        $client = $this;
        if (! empty($secret)) {
            $client = $this->withHeader('X-Telegram-Bot-Api-Secret-Token', $secret);
        }

        $client->postJson(route('telegram.webhook'), $payload)
            ->assertOk()
            ->assertJson(['ok' => true]);

        $withdrawal->refresh();
        $this->assertSame('failed', $withdrawal->status);

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
}
