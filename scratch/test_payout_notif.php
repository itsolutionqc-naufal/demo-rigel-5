<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SaleTransaction;
use App\Models\User;
use App\Services\TelegramService;
use App\Support\TelegramMessageFormatter;

echo "Memulai Tes Notifikasi Rigel Payout Bot (ID 5)...\n";

$botId = 5;
$tgService = TelegramService::forBot($botId);

if (!$tgService) {
    echo "Gagal membuat layanan Telegram. Pastikan Bot ID 5 ada dan aktif.\n";
    exit;
}

$user = User::first();
if (!$user) {
    echo "Gagal: Tidak ada user di database untuk membuat transaksi.\n";
    exit;
}

// 1. Buat transaksi REAL di database agar bisa di-update statusnya
$transactionCode = 'PAY-TEST-' . time();
$transaction = SaleTransaction::create([
    'transaction_code' => $transactionCode,
    'user_id' => $user->id,
    'service_name' => 'Payout Service',
    'amount' => 1250000,
    'status' => 'pending',
    'transaction_type' => 'withdrawal',
    'payment_method' => 'BANK BCA',
    'account_number' => '1234567890',
    'account_name' => 'Tester User',
    'description' => 'Test Payout via Bot'
]);

echo "Transaksi baru dibuat di database: " . $transaction->transaction_code . "\n";

$chatId = $tgService->getChatId();
echo "Mengirim ke Chat ID: " . $chatId . "\n";

// 2. Siapkan Pesan
$message = TelegramMessageFormatter::heading('NOTIFIKASI PENARIKAN (PAYOUT)');
$message .= TelegramMessageFormatter::bullet('ID Penarikan', (string) $transactionCode);
$message .= TelegramMessageFormatter::bullet('User', 'Tester User');
$message .= TelegramMessageFormatter::bullet('Metode', 'BANK BCA');
$message .= TelegramMessageFormatter::bullet('Rekening', '1234567890');
$message .= TelegramMessageFormatter::bullet('Nominal', 'Rp ' . number_format($transaction->amount, 0, ',', '.'), false);
$message .= TelegramMessageFormatter::divider();
$message .= TelegramMessageFormatter::bullet('Status', 'Menunggu Pembayaran');

// 3. Tombol dengan teks Berhasil/Gagal, tapi action tetap approve/reject agar sistem mengenali
$buttons = [
    ['text' => '✅ BERHASIL', 'callback_data' => "approve|{$botId}|{$transactionCode}"],
    ['text' => '❌ GAGAL', 'callback_data' => "reject|{$botId}|{$transactionCode}"],
];

try {
    $result = $tgService->sendMessageWithButtons(
        $chatId,
        $message,
        $buttons
    );

    if ($result['success']) {
        echo "Selesai! Silakan klik tombol 'BERHASIL' di Telegram untuk mengetes perubahan status di database.\n";
    } else {
        echo "Gagal kirim: " . ($result['error'] ?? 'Unknown error') . "\n";
    }
} catch (\Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage() . "\n";
}
