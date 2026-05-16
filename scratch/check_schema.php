<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = DB::select('DESCRIBE sale_transactions');
foreach ($columns as $col) {
    echo "Field: {$col->Field} | Type: {$col->Type}\n";
}
