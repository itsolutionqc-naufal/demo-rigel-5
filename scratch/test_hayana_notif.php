<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SaleTransaction;
use App\Models\User;
use App\Services\NotificationService;

echo "Memulai Tes Transaksi hayana Bot...\n";

// 1. Ambil user pertama sebagai contoh
$user = User::first();

// 2. Buat transaksi dummy untuk Hago
$transaction = SaleTransaction::create([
    'transaction_code' => 'TEST-HAYANA-' . time(),
    'user_id' => $user->id,
    'service_name' => 'Hago',
    'amount' => 50000,
    'status' => 'success', // Status success akan memicu notifikasi final status
    'payment_method' => 'DANA',
    'payment_number' => '08123456789',
    'transaction_type' => 'topup',
    'nickname' => 'Tester Hayana',
    'user_id_input' => '123456',
]);

echo "Transaksi dummy dibuat: " . $transaction->transaction_code . "\n";

// 3. Kirim notifikasi
try {
    $notifService = app(NotificationService::class);
    // Kita panggil manual pengiriman notifikasinya
    $reflection = new ReflectionClass($notifService);
    $method = $reflection->getMethod('notifyTelegramTransactionFinalStatus');
    $method->setAccessible(true);
    $method->invoke($notifService, $transaction, 'success');

    echo "Selesai! Silakan cek Telegram kakak. Seharusnya ada pesan masuk dari hayana Bot.\n";
} catch (\Exception $e) {
    echo "Gagal mengirim notifikasi: " . $e->getMessage() . "\n";
}
