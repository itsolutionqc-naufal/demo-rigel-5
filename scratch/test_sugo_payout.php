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

echo "Membuat transaksi dummy untuk bot {$bot->name}...\n";

// Create dummy transaction with correct fields
$transactionCode = 'SUGO-TEST-' . time();
$transaction = new SaleTransaction();
$transaction->transaction_code = $transactionCode;
$transaction->user_id = 28; 
$transaction->customer_name = 'Dummy Sugo User';
$transaction->amount = 50000;
$transaction->status = 'pending';
$transaction->transaction_type = 'withdrawal';
$transaction->service_name = 'SUGO';
$transaction->save();

echo "Transaksi baru dibuat: {$transactionCode}\n";

$tg = new TelegramService($bot->token, $bot->chat_id);

$message = "🟢 *PENGAJUAN PENCAIRAN (SUGO)*\n\n";
$message .= "Kode: `{$transactionCode}`\n";
$message .= "User: `Dummy Sugo User` (ID: 28)\n";
$message .= "Jumlah: `Rp 50.000`\n";
$message .= "Layanan: `SUGO`\n\n";
$message .= "Silakan proses transaksi ini:";

// Flat list for Sugo Bot's TelegramService format
$buttons = [
    ['text' => '✅ BERHASIL', 'callback_data' => "approve|{$botId}|{$transactionCode}"],
    ['text' => '❌ GAGAL', 'callback_data' => "reject|{$botId}|{$transactionCode}"],
];

echo "Mengirim ke Chat ID: {$bot->chat_id}...\n";
$response = $tg->sendMessageWithButtons($bot->chat_id, $message, $buttons);

if (isset($response['success']) && $response['success']) {
    echo "Selesai! Silakan klik tombol di Telegram untuk mengetes.\n";
} else {
    echo "Gagal mengirim: " . ($response['error'] ?? 'unknown error') . "\n";
}
