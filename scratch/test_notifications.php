<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Log;

$app->make(Kernel::class)->bootstrap();

// Test for Lapajar (Layla)
echo "Testing Lapajar (Layla)...\n";
$trx1 = SaleTransaction::create([
    'transaction_code' => 'TEST-LAPAJAR-' . time(),
    'service_name' => 'Layla',
    'amount' => 10000,
    'status' => 'pending',
    'transaction_type' => 'topup',
    'user_id' => User::where('role', 'user')->first()?->id ?? 1,
]);

echo "Updating status to success for Layla...\n";
$trx1->update(['status' => 'success']);
echo "Check your Lapajar Bot!\n\n";

// Test for hayana (Hago)
echo "Testing hayana (Hago)...\n";
$trx2 = SaleTransaction::create([
    'transaction_code' => 'TEST-HAYANA-' . time(),
    'service_name' => 'Hago',
    'amount' => 20000,
    'status' => 'pending',
    'transaction_type' => 'topup',
    'user_id' => User::where('role', 'user')->first()?->id ?? 1,
]);

echo "Updating status to success for Hago...\n";
$trx2->update(['status' => 'success']);
echo "Check your hayana Bot!\n";

echo "\nDone. Please check your Telegram bots for the test notifications.\n";
