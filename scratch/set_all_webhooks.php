<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TelegramBot;
use App\Services\TelegramService;

$newUrl = "https://obtaining-eos-stockholm-mary.trycloudflare.com/telegram/webhook";
$bots = TelegramBot::where('is_active', 1)->get();

echo "Mendaftarkan Webhook baru untuk " . count($bots) . " bot...\n";
echo "URL: {$newUrl}\n\n";

foreach ($bots as $bot) {
    if (empty($bot->token)) {
        echo "[-] Bot ID {$bot->id} ({$bot->name}) tidak punya token. Skip.\n";
        continue;
    }

    try {
        $tg = new TelegramService($bot->token, $bot->chat_id);
        $result = $tg->setWebhook($newUrl);
        
        if ($result['ok']) {
            echo "[+] Bot ID {$bot->id} ({$bot->name}) BERHASIL di-set.\n";
        } else {
            echo "[x] Bot ID {$bot->id} ({$bot->name}) GAGAL: " . ($result['description'] ?? 'error') . "\n";
        }
    } catch (\Exception $e) {
        echo "[!] Bot ID {$bot->id} ({$bot->name}) EXCEPTION: " . $e->getMessage() . "\n";
    }
}

echo "\nSemua selesai!\n";
