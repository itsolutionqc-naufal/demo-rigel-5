<?php

use App\Models\SaleTransaction;
use App\Models\TelegramBot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('approves transaction via bot-aware callback using the correct bot token', function () {
    $bot = TelegramBot::create([
        'name' => 'Test Bot',
        'username' => 'test_bot',
        'token' => 'BOT_TOKEN_123',
        'chat_id' => '999',
        'is_active' => true,
    ]);

    $transaction = SaleTransaction::factory()->create([
        'transaction_code' => 'TRX-TEST-123',
        'status' => 'pending',
        'transaction_type' => 'topup',
    ]);

    Http::fake(function (Request $request) {
        return Http::response(['ok' => true, 'result' => ['message_id' => 1]], 200);
    });

    $payload = [
        'callback_query' => [
            'id' => 'cb_1',
            'from' => ['id' => 1, 'first_name' => 'Admin'],
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 999],
                'text' => 'dummy',
            ],
            'data' => "approve|{$bot->id}|{$transaction->transaction_code}",
        ],
    ];

    $headers = [];
    if (filled(env('TELEGRAM_WEBHOOK_SECRET'))) {
        $headers['X-Telegram-Bot-Api-Secret-Token'] = env('TELEGRAM_WEBHOOK_SECRET');
    }

    $this->postJson('/telegram/webhook', $payload, $headers)->assertOk()->assertJson(['ok' => true]);

    expect($transaction->fresh()->status)->toBe('success');

    Http::assertSent(function (Request $request) {
        return str_contains($request->url(), 'https://api.telegram.org/botBOT_TOKEN_123/answerCallbackQuery');
    });
});
