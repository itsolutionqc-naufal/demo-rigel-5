<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TelegramBot;
use App\Models\SaleTransaction;
use App\Services\TelegramService;

$botId = 2; // Rigel Sugo Bot
$bot = TelegramBot::find($botId);

if (!$bot) {
    echo "Bot ID {$botId} tidak ditemukan.\n";
    exit;
}

echo "Membuat transaksi dummy dengan GAMBAR untuk bot {$bot->name}...\n";

// Create dummy transaction
$transactionCode = 'SUGO-IMG-' . time();
$transaction = new SaleTransaction();
$transaction->transaction_code = $transactionCode;
$transaction->user_id = 28; 
$transaction->customer_name = 'Dummy Image User';
$transaction->amount = 75000;
$transaction->status = 'pending';
$transaction->transaction_type = 'withdrawal';
$transaction->service_name = 'SUGO';
// Path relative to public/
$imagePath = 'images/logo.png'; 
$transaction->proof_image = $imagePath;
$transaction->save();

echo "Transaksi baru dibuat: {$transactionCode}\n";

$tg = new TelegramService($bot->token, $bot->chat_id);

$message = "🟢 *PENGAJUAN PENCAIRAN + BUKTI (SUGO)*\n\n";
$message .= "Kode: `{$transactionCode}`\n";
$message .= "User: `Dummy Image User` (ID: 28)\n";
$message .= "Jumlah: `Rp 75.000`\n";
$message .= "Layanan: `SUGO`\n\n";
$message .= "Bukti transfer terlampir di bawah.";

$buttons = [
    ['text' => '✅ BERHASIL', 'callback_data' => "approve|{$botId}|{$transactionCode}"],
    ['text' => '❌ GAGAL', 'callback_data' => "reject|{$botId}|{$transactionCode}"],
];

echo "Mengirim ke Chat ID: {$bot->chat_id} dengan gambar {$imagePath}...\n";
$response = $tg->sendPhotoWithButtons($bot->chat_id, $message, $imagePath, $buttons);

if (isset($response['success']) && $response['success']) {
    echo "Selesai! Silakan cek Bot Telegram Anda.\n";
} else {
    echo "Gagal mengirim: " . ($response['error'] ?? 'unknown error') . "\n";
    print_r($response);
}
