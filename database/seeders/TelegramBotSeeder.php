<?php

namespace Database\Seeders;

use App\Models\TelegramBot;
use App\Models\Service;
use Illuminate\Database\Seeder;

class TelegramBotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bot untuk Layla
        $laylaBot = TelegramBot::updateOrCreate(
            ['username' => 'LaylaNotificationBot'],
            [
                'name' => 'Layla Bot',
                'token' => '8679277444:AAG238dh_axeXf856duFX5-KsrLqHaWb9pY',
                'chat_id' => '8601995666',
                'is_active' => true,
            ]
        );

        // Assign bot ke semua service yang category-nya mengandung kata "layla"
        Service::where('category', 'LIKE', '%layla%')
            ->orWhere('name', 'LIKE', '%layla%')
            ->update(['telegram_bot_id' => $laylaBot->id]);

        $this->command->info('✅ Telegram Bot untuk Layla berhasil ditambahkan!');
        $this->command->info('📋 Bot assigned ke service Layla!');
    }
}
