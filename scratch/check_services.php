<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$services = App\Models\Service::with("telegramBot")->get();
foreach ($services as $s) {
    echo "Service: " . $s->name . " | Bot: " . ($s->telegramBot?->name ?? "NONE") . " | ChatID: " . ($s->telegram_chat_id ?? $s->telegramBot?->chat_id ?? "NONE") . "\n";
}
