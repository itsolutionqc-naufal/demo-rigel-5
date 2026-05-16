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

echo "Memulai Tes Notifikasi dengan GAMBAR RELATIF + TOMBOL...\n";

$user = User::first();
$service = Service::where('name', 'Hago')->first();

// 1. Buat transaksi dummy baru
$transactionCode = 'TRX-IMG-FINAL-' . time();
$transaction = SaleTransaction::create([
    'transaction_code' => $transactionCode,
    'user_id' => $user->id,
    'service_name' => 'Hago',
    'amount' => 300000,
    'status' => 'pending',
    'payment_method' => 'QRIS',
    'payment_number' => 'RIGEL-PAY',
    'transaction_type' => 'topup',
    'nickname' => 'Tester Gambar Final',
    'user_id_input' => '111222',
    'proof_image' => 'uploads/images/qris/1770260356_Apa-itu-QRIS.jpeg'
]);

echo "Transaksi baru dibuat: " . $transaction->transaction_code . "\n";

// 2. Siapkan Pesan & Tombol
$botKey = ($service && $service->telegramBot && $service->telegramBot->is_active) 
            ? (string) $service->telegramBot->id 
            : 'default';

$message = TelegramMessageFormatter::heading('KONFIRMASI TOP UP (DENGAN GAMBAR FINAL)');
$message .= TelegramMessageFormatter::bullet('Kode Transaksi', (string) $transactionCode);
$message .= "\n";
$message .= TelegramMessageFormatter::bullet('Layanan', 'Hago');
$message .= TelegramMessageFormatter::bullet('ID Pengguna', '111222');
$message .= TelegramMessageFormatter::bullet('Nickname', 'Tester Gambar');
$message .= TelegramMessageFormatter::bullet('Nominal', 'Rp 300.000', false);
$message .= TelegramMessageFormatter::bullet('Pembayaran', 'QRIS');
$message .= TelegramMessageFormatter::divider();
$message .= TelegramMessageFormatter::bullet('Status', 'Menunggu konfirmasi admin');

$buttons = [
    ['text' => '✅ BERHASIL', 'callback_data' => "approve|{$botKey}|{$transactionCode}"],
    ['text' => '❌ GAGAL', 'callback_data' => "reject|{$botKey}|{$transactionCode}"],
];

// 3. Kirim via TelegramService (Kirim Gambar dengan RELATIVE PATH)
try {
    $tgService = TelegramService::forService($service);
    
    // Gunakan path relatif dari folder PUBLIC
    $photoPath = "uploads/images/qris/1770260356_Apa-itu-QRIS.jpeg";
    
    echo "Mencoba mengirim gambar RELATIF: " . $photoPath . "\n";
    
    $result = $tgService->sendPhotoWithButtons(
        $tgService->getChatId(),
        $message,
        $photoPath,
        $buttons
    );

    if ($result['success']) {
        echo "Selesai! Silakan cek Telegram. GAMBAR HARUSNYA MUNCUL SEKARANG.\n";
    } else {
        echo "Gagal kirim gambar: " . ($result['error'] ?? 'Unknown error') . "\n";
    }
} catch (\Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage() . "\n";
}
