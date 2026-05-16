<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TelegramBot;
use App\Services\TelegramService;

$botId = 2; // Rigel Sugo Bot
$bot = TelegramBot::find($botId);

if (!$bot) {
    echo "Bot ID {$botId} tidak ditemukan.\n";
    exit;
}

echo "Mengecek Bot: {$bot->name} (@{$bot->username})\n";
$tg = new TelegramService($bot->token, $bot->chat_id);

echo "Webhook Info:\n";
print_r($tg->getWebhookInfo());

echo "\nMengetes pengiriman pesan...\n";
$response = $tg->sendMessage($bot->chat_id, "Tes koneksi dari bot {$bot->name}.");

echo "Response from Telegram:\n";
print_r($response);
