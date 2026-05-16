<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SaleTransaction;

$trx = SaleTransaction::where('transaction_code', 'PAY-TEST-1778846868')->first();
if ($trx) {
    echo "TRANSACTION FOUND: " . $trx->transaction_code . " STATUS: " . $trx->status . "\n";
    print_r($trx->toArray());
} else {
    echo "TRANSACTION NOT FOUND\n";
}
