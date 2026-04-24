<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Settings global untuk komisi sudah tidak digunakan lagi
        // Komisi sekarang per-service (di tabel services)
        
        // Hanya seed WhatsApp settings jika diperlukan
        $settings = [
            // Tambahkan settings lain di sini jika diperlukan
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                ]
            );
        }
    }
}
