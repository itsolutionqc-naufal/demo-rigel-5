<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TelegramBot;

$bot = TelegramBot::find(5);

if ($bot) {
    echo "Bot Found:\n";
    print_r($bot->toArray());
} else {
    echo "Bot with ID 5 NOT found in the database.\n";
    
    echo "\nAll Bots in database:\n";
    $allBots = TelegramBot::all();
    foreach ($allBots as $b) {
        echo "ID: {$b->id} - Name: {$b->name} ({$b->username})\n";
    }
}
