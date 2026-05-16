<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TelegramBot;

$bots = TelegramBot::all();
foreach ($bots as $bot) {
    echo "ID: {$bot->id} | Name: {$bot->name} | Username: {$bot->username} | Active: {$bot->is_active}\n";
}
