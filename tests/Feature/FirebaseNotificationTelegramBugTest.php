<?php

use App\Jobs\SendPushNotificationJob;
use App\Models\DeviceToken;
use App\Models\SaleTransaction;
use App\Models\TelegramBot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

/**
 * Property 1: Bug Condition - Firebase Notification Tidak Terkirim Saat Telegram Approve/Reject
 * 
 * CRITICAL: Test ini HARUS GAGAL pada kode unfixed - kegagalan mengkonfirmasi bug ada
 * JANGAN mencoba memperbaiki test atau kode ketika test gagal
 * 
 * GOAL: Surface counterexamples yang mendemonstrasikan bug ada
 * 
 * Bug Condition dari design:
 * - Admin klik approve/reject di bot Telegram
 * - Database update berhasil
 * - notifyTransactionStatusChange dipanggil synchronously
 * - Firebase notification TIDAK terkirim (atau tidak di-dispatch ke job queue)
 * 
 * Expected Behavior setelah fix:
 * - firebaseNotificationDispatched(result) = true
 * - jobQueueContainsNotification(userId) = true
 * - notificationEventuallyDelivered(userId) = true
 * 
 * Requirements: 1.1, 1.2, 1.3, 1.4
 */

it('dispatches Firebase notification job when admin approves transaction via Telegram', function () {
    Queue::fake();
    
    // Setup: Create user with device token
    $user = User::factory()->create([
        'role' => User::ROLE_MARKETING,
        'name' => 'Test User',
    ]);
    
    DeviceToken::create([
        'user_id' => $user->id,
        'token' => 'test-firebase-token-123',
        'platform' => 'android',
    ]);
    
    // Setup: Create Telegram bot
    $bot = TelegramBot::create([
        'name' => 'Test Bot',
        'username' => 'test_bot',
        'token' => 'BOT_TOKEN_123',
        'chat_id' => '999',
        'is_active' => true,
    ]);
    
    // Setup: Create pending transaction
    $transaction = SaleTransaction::factory()->create([
        'user_id' => $user->id,
        'transaction_code' => 'TRX-TEST-APPROVE-001',
        'status' => 'pending',
        'transaction_type' => 'topup',
        'service_name' => 'Mobile Legends',
        'amount' => 50000,
        'commission_amount' => 5000,
    ]);
    
    // Mock Telegram API responses
    Http::fake(function (Request $request) {
        return Http::response(['ok' => true, 'result' => ['message_id' => 1]], 200);
    });
    
    // Simulate Telegram webhook callback for approve action
    $payload = [
        'callback_query' => [
            'id' => 'cb_approve_001',
            'from' => ['id' => 1, 'first_name' => 'Admin'],
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 999],
                'text' => 'Transaction approval request',
            ],
            'data' => "approve|{$bot->id}|{$transaction->transaction_code}",
        ],
    ];
    
    $headers = [];
    if (filled(env('TELEGRAM_WEBHOOK_SECRET'))) {
        $headers['X-Telegram-Bot-Api-Secret-Token'] = env('TELEGRAM_WEBHOOK_SECRET');
    }
    
    // Act: Send webhook request
    $response = $this->postJson('/telegram/webhook', $payload, $headers);
    
    // Assert: Response is OK
    $response->assertOk()->assertJson(['ok' => true]);
    
    // Assert: Transaction status updated to success
    expect($transaction->fresh()->status)->toBe('success');
    
    // Assert: Firebase notification job dispatched to queue
    // EXPECTED TO FAIL on unfixed code because notification is called synchronously
    // and not dispatched to job queue
    Queue::assertPushed(SendPushNotificationJob::class, function ($job) use ($user) {
        return $job->userId === $user->id
            && str_contains($job->title, 'Transaksi Berhasil')
            && str_contains($job->body, 'TRX-TEST-APPROVE-001');
    });
    
    // Assert: At least one job dispatched (could be multiple for user + admin notifications)
    Queue::assertPushed(SendPushNotificationJob::class);
});

it('dispatches Firebase notification job when admin rejects transaction via Telegram', function () {
    Queue::fake();
    
    // Setup: Create user with device token
    $user = User::factory()->create([
        'role' => User::ROLE_MARKETING,
        'name' => 'Test User',
    ]);
    
    DeviceToken::create([
        'user_id' => $user->id,
        'token' => 'test-firebase-token-456',
        'platform' => 'android',
    ]);
    
    // Setup: Create Telegram bot
    $bot = TelegramBot::create([
        'name' => 'Test Bot',
        'username' => 'test_bot',
        'token' => 'BOT_TOKEN_456',
        'chat_id' => '888',
        'is_active' => true,
    ]);
    
    // Setup: Create pending transaction
    $transaction = SaleTransaction::factory()->create([
        'user_id' => $user->id,
        'transaction_code' => 'TRX-TEST-REJECT-002',
        'status' => 'pending',
        'transaction_type' => 'topup',
        'service_name' => 'Free Fire',
        'amount' => 30000,
        'commission_amount' => 3000,
    ]);
    
    // Mock Telegram API responses
    Http::fake(function (Request $request) {
        return Http::response(['ok' => true, 'result' => ['message_id' => 1]], 200);
    });
    
    // Simulate Telegram webhook callback for reject action
    $payload = [
        'callback_query' => [
            'id' => 'cb_reject_002',
            'from' => ['id' => 2, 'first_name' => 'Admin'],
            'message' => [
                'message_id' => 20,
                'chat' => ['id' => 888],
                'text' => 'Transaction rejection request',
            ],
            'data' => "reject|{$bot->id}|{$transaction->transaction_code}",
        ],
    ];
    
    $headers = [];
    if (filled(env('TELEGRAM_WEBHOOK_SECRET'))) {
        $headers['X-Telegram-Bot-Api-Secret-Token'] = env('TELEGRAM_WEBHOOK_SECRET');
    }
    
    // Act: Send webhook request
    $response = $this->postJson('/telegram/webhook', $payload, $headers);
    
    // Assert: Response is OK
    $response->assertOk()->assertJson(['ok' => true]);
    
    // Assert: Transaction status updated to failed
    expect($transaction->fresh()->status)->toBe('failed');
    
    // Assert: Firebase notification job dispatched to queue
    // EXPECTED TO FAIL on unfixed code because notification is called synchronously
    // and not dispatched to job queue
    Queue::assertPushed(SendPushNotificationJob::class, function ($job) use ($user) {
        return $job->userId === $user->id
            && str_contains($job->title, 'Transaksi Ditolak')
            && str_contains($job->body, 'TRX-TEST-REJECT-002');
    });
    
    // Assert: At least one job dispatched
    Queue::assertPushed(SendPushNotificationJob::class);
});

it('dispatches Firebase notification jobs for both user and admin when transaction approved via Telegram', function () {
    Queue::fake();
    
    // Setup: Create user with device token
    $user = User::factory()->create([
        'role' => User::ROLE_MARKETING,
        'name' => 'Marketing User',
    ]);
    
    DeviceToken::create([
        'user_id' => $user->id,
        'token' => 'user-firebase-token',
        'platform' => 'android',
    ]);
    
    // Setup: Create admin with device token
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
        'name' => 'Admin User',
    ]);
    
    DeviceToken::create([
        'user_id' => $admin->id,
        'token' => 'admin-firebase-token',
        'platform' => 'android',
    ]);
    
    // Setup: Create Telegram bot
    $bot = TelegramBot::create([
        'name' => 'Test Bot',
        'username' => 'test_bot',
        'token' => 'BOT_TOKEN_789',
        'chat_id' => '777',
        'is_active' => true,
    ]);
    
    // Setup: Create pending transaction
    $transaction = SaleTransaction::factory()->create([
        'user_id' => $user->id,
        'transaction_code' => 'TRX-TEST-MULTI-003',
        'status' => 'pending',
        'transaction_type' => 'topup',
        'service_name' => 'PUBG Mobile',
        'amount' => 100000,
        'commission_amount' => 10000,
    ]);
    
    // Mock Telegram API responses
    Http::fake(function (Request $request) {
        return Http::response(['ok' => true, 'result' => ['message_id' => 1]], 200);
    });
    
    // Simulate Telegram webhook callback for approve action
    $payload = [
        'callback_query' => [
            'id' => 'cb_multi_003',
            'from' => ['id' => 3, 'first_name' => 'Admin'],
            'message' => [
                'message_id' => 30,
                'chat' => ['id' => 777],
                'text' => 'Transaction approval request',
            ],
            'data' => "approve|{$bot->id}|{$transaction->transaction_code}",
        ],
    ];
    
    $headers = [];
    if (filled(env('TELEGRAM_WEBHOOK_SECRET'))) {
        $headers['X-Telegram-Bot-Api-Secret-Token'] = env('TELEGRAM_WEBHOOK_SECRET');
    }
    
    // Act: Send webhook request
    $response = $this->postJson('/telegram/webhook', $payload, $headers);
    
    // Assert: Response is OK
    $response->assertOk()->assertJson(['ok' => true]);
    
    // Assert: Transaction status updated
    expect($transaction->fresh()->status)->toBe('success');
    
    // Assert: Firebase notification jobs dispatched for user
    // EXPECTED TO FAIL on unfixed code
    Queue::assertPushed(SendPushNotificationJob::class, function ($job) use ($user) {
        return $job->userId === $user->id;
    });
    
    // Assert: Firebase notification jobs dispatched for admin
    // EXPECTED TO FAIL on unfixed code
    Queue::assertPushed(SendPushNotificationJob::class, function ($job) use ($admin) {
        return $job->userId === $admin->id;
    });
    
    // Assert: At least 2 jobs dispatched (one for user, one for admin)
    Queue::assertPushed(SendPushNotificationJob::class, 2);
});
