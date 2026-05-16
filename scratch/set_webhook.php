<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TelegramBot;
use App\Services\TelegramService;

$botId = 5;
$bot = TelegramBot::find($botId);
if (!$bot) {
    echo "Bot 5 not found\n";
    exit;
}

$tunnelUrl = "https://k-syndrome-region.trycloudflare.com/telegram/webhook";
$tg = new TelegramService($bot->token, $bot->chat_id);

echo "Setting webhook for bot {$botId} to: {$tunnelUrl}\n";
$result = $tg->setWebhook($tunnelUrl);

if ($result['ok']) {
    echo "Webhook SET successfully!\n";
} else {
    echo "FAILED to set webhook: " . ($result['description'] ?? 'unknown error') . "\n";
}

print_r($tg->getWebhookInfo());
