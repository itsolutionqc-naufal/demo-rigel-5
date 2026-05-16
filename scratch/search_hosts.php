<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use App\Models\HostSubmission;
use Illuminate\Contracts\Console\Kernel;

$app->make(Kernel::class)->bootstrap();

$nicknames = ['Lapajar', 'hayana'];

foreach ($nicknames as $nickname) {
    echo "Searching for: $nickname\n";
    $results = HostSubmission::where('nickname', 'like', "%$nickname%")->get();
    if ($results->isEmpty()) {
        echo "No results found for $nickname.\n";
    } else {
        foreach ($results as $result) {
            echo "ID: " . $result->id . "\n";
            echo "Nickname: " . $result->nickname . "\n";
            echo "WhatsApp: " . $result->whatsapp_number . "\n";
            echo "-------------------\n";
        }
    }
    echo "\n";
}
