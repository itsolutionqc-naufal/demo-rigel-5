<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhatsAppTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set default WhatsApp templates if they don't exist
        $templates = [
            [
                'key' => 'whatsapp_wallet_template',
                'value' => 'Halo%20kak,%20mau%20nanya%20soal%20status%20pencairan%20saya',
                'type' => 'text',
                'group' => 'whatsapp'
            ],
            [
                'key' => 'whatsapp_job_template', 
                'value' => 'Halo%20kak,%20mau%20nanya%20soal%20status%20transaksi%20saya',
                'type' => 'text',
                'group' => 'whatsapp'
            ]
        ];

        foreach ($templates as $template) {
            Setting::updateOrCreate(
                ['key' => $template['key']],
                [
                    'value' => $template['value'],
                    'type' => $template['type'],
                    'group' => $template['group']
                ]
            );
        }

        $this->command->info('WhatsApp templates seeded successfully!');
    }
}
