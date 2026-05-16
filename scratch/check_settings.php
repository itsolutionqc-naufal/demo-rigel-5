<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Setting;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "app.download_prompt_enabled: " . Setting::get('app.download_prompt_enabled', 'MISSING') . "\n";
echo "app.download_url: " . Setting::get('app.download_url', 'MISSING') . "\n";
echo "app.download_prompt_title: " . Setting::get('app.download_prompt_title', 'MISSING') . "\n";
echo "app.download_prompt_body: " . Setting::get('app.download_prompt_body', 'MISSING') . "\n";
