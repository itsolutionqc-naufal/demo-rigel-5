<?php

use App\Services\TelegramService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('falls back to sendMessage when sendPhoto returns 400', function () {
    $photoPath = public_path('uploads/images/job-user/test-telegram.jpg');
    if (! is_dir(dirname($photoPath))) {
        mkdir(dirname($photoPath), 0755, true);
    }
    file_put_contents($photoPath, 'fake-image-bytes');

    Http::fake([
        'https://api.telegram.org/botTOKEN/sendPhoto' => Http::response(['ok' => false, 'description' => 'Bad Request'], 400),
        'https://api.telegram.org/botTOKEN/sendMessage' => Http::response(['ok' => true, 'result' => ['message_id' => 123]], 200),
    ]);

    $service = new TelegramService('TOKEN', 'CHAT_ID');
    $result = $service->sendPhotoWithButtons(
        'CHAT_ID',
        '*hello*',
        'uploads/images/job-user/test-telegram.jpg',
        [['text' => '✅', 'callback_data' => 'approve|default|TRX-1']]
    );

    expect($result['success'])->toBeTrue();
    expect($result['fallback_used'] ?? false)->toBeTrue();

    Http::assertSent(fn (Request $request) => str_contains($request->url(), '/sendPhoto'));
    Http::assertSent(fn (Request $request) => str_contains($request->url(), '/sendMessage'));

    @unlink($photoPath);
});

