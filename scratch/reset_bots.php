<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Hapus semua bot lama
App\Models\TelegramBot::query()->delete();
echo "Semua bot lama telah dihapus.\n";

// 2. Reset semua service
App\Models\Service::query()->update(["telegram_bot_id" => null, "telegram_chat_id" => null]);
echo "Semua sambungan layanan telah direset.\n";

// 3. Buat bot baru
$lapajar = App\Models\TelegramBot::create([
    "name" => "Lapajar Bot",
    "token" => "",
    "username" => "lapajar_bot",
    "chat_id" => "",
    "is_active" => true
]);

$hayana = App\Models\TelegramBot::create([
    "name" => "hayana Bot",
    "token" => "",
    "username" => "hayana_bot",
    "chat_id" => "",
    "is_active" => true
]);

echo "Bot baru telah dibuat (ID Lapajar: {$lapajar->id}, ID hayana: {$hayana->id}).\n";

// 4. Hubungkan layanan ke bot baru
App\Models\Service::whereIn("name", ["Layla", "Papaya", "Honey Jar"])->update(["telegram_bot_id" => $lapajar->id]);
App\Models\Service::whereIn("name", ["Hago", "Voya", "Xena"])->update(["telegram_bot_id" => $hayana->id]);

echo "Layanan telah disambungkan kembali ke bot masing-masing.\n";
