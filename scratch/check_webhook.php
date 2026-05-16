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

$tg = new TelegramService($bot->token, $bot->chat_id);
$info = $tg->getWebhookInfo();
print_r($info);
