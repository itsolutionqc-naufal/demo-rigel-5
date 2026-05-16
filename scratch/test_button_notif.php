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

echo "Memulai Tes Notifikasi dengan Tombol (Simulasi Controller)...\n";

$user = User::first();
$service = Service::where('name', 'Hago')->first();

// 1. Buat transaksi dummy status pending
$transactionCode = 'TRX-BTN-' . time();
$transaction = SaleTransaction::create([
    'transaction_code' => $transactionCode,
    'user_id' => $user->id,
    'service_name' => 'Hago',
    'amount' => 150000,
    'status' => 'pending',
    'payment_method' => 'GOPAY',
    'payment_number' => '081222333444',
    'transaction_type' => 'topup',
    'nickname' => 'Tester Tombol',
    'user_id_input' => '999111',
]);

echo "Transaksi baru dibuat: " . $transaction->transaction_code . "\n";

// 2. Siapkan Pesan & Tombol (Logika dari MobileDashboardController)
$botKey = ($service && $service->telegramBot && $service->telegramBot->is_active) 
            ? (string) $service->telegramBot->id 
            : 'default';

$message = TelegramMessageFormatter::heading('KONFIRMASI TOP UP BARU');
$message .= TelegramMessageFormatter::bullet('Kode Transaksi', (string) $transactionCode);
$message .= "\n";
$message .= TelegramMessageFormatter::bullet('Layanan', 'Hago');
$message .= TelegramMessageFormatter::bullet('ID Pengguna', '999111');
$message .= TelegramMessageFormatter::bullet('Nickname', 'Tester Tombol');
$message .= TelegramMessageFormatter::bullet('Nominal', 'Rp 150.000', false);
$message .= TelegramMessageFormatter::bullet('Pembayaran', 'GOPAY');
$message .= TelegramMessageFormatter::bullet('No. Rekening', '081222333444');
$message .= TelegramMessageFormatter::divider();
$message .= TelegramMessageFormatter::bullet('Status', 'Menunggu konfirmasi admin');

$buttons = [
    ['text' => '✅ BERHASIL', 'callback_data' => "approve|{$botKey}|{$transactionCode}"],
    ['text' => '❌ GAGAL', 'callback_data' => "reject|{$botKey}|{$transactionCode}"],
];

// 3. Kirim via TelegramService
try {
    $tgService = TelegramService::forService($service);
    $result = $tgService->sendMessageWithButtons(
        $tgService->getChatId(),
        $message,
        $buttons
    );

    if ($result['success']) {
        echo "Selesai! Silakan cek Telegram. Harusnya muncul pesan dengan tombol Berhasil/Gagal.\n";
    } else {
        echo "Gagal kirim: " . ($result['error'] ?? 'Unknown error') . "\n";
    }
} catch (\Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage() . "\n";
}
