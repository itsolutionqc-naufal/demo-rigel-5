<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SaleTransaction;
use App\Models\User;
use App\Models\Service;
use App\Services\TelegramService;
use App\Support\TelegramMessageFormatter;

echo "Memulai Tes Notifikasi LAPAJAR Bot (Layanan Layla)...\n";

$user = User::first();
$service = Service::where('name', 'Layla')->first();

// 1. Buat transaksi dummy baru
$transactionCode = 'TRX-LAPAJAR-' . time();
$transaction = SaleTransaction::create([
    'transaction_code' => $transactionCode,
    'user_id' => $user->id,
    'service_name' => 'Layla',
    'amount' => 500000,
    'status' => 'pending',
    'payment_method' => 'BANK TRANSFER',
    'payment_number' => '123-456-789',
    'transaction_type' => 'topup',
    'nickname' => 'Tester Lapajar',
    'user_id_input' => 'LAP-001',
    'proof_image' => 'uploads/images/qris/1770260356_Apa-itu-QRIS.jpeg'
]);

echo "Transaksi baru dibuat: " . $transaction->transaction_code . "\n";

// 2. Siapkan Pesan & Tombol
$botKey = ($service && $service->telegramBot && $service->telegramBot->is_active) 
            ? (string) $service->telegramBot->id 
            : 'default';

$message = TelegramMessageFormatter::heading('NOTIFIKASI LAPAJAR BOT (LAYLA)');
$message .= TelegramMessageFormatter::bullet('Kode', (string) $transactionCode);
$message .= TelegramMessageFormatter::bullet('Layanan', 'Layla');
$message .= TelegramMessageFormatter::bullet('ID', 'LAP-001');
$message .= TelegramMessageFormatter::bullet('Nominal', 'Rp 500.000', false);
$message .= TelegramMessageFormatter::divider();
$message .= TelegramMessageFormatter::bullet('Status', 'Menunggu Approval Lapajar');

$buttons = [
    ['text' => '✅ APPROVE', 'callback_data' => "approve|{$botKey}|{$transactionCode}"],
    ['text' => '❌ REJECT', 'callback_data' => "reject|{$botKey}|{$transactionCode}"],
];

// 3. Kirim via TelegramService
try {
    $tgService = TelegramService::forService($service);
    $photoPath = "uploads/images/qris/1770260356_Apa-itu-QRIS.jpeg";
    
    $result = $tgService->sendPhotoWithButtons(
        $tgService->getChatId(),
        $message,
        $photoPath,
        $buttons
    );

    if ($result['success']) {
        echo "Selesai! Silakan cek Lapajar Bot di Telegram.\n";
    } else {
        echo "Gagal kirim: " . ($result['error'] ?? 'Unknown error') . "\n";
    }
} catch (\Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage() . "\n";
}
