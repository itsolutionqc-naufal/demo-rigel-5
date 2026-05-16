<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Voya',
                'description' => 'Top up coins Voya - Proses cepat dan aman',
                'category' => 'Live Streaming',
                'image' => 'services/voya.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.00,
                'whatsapp_number' => '6281234567890',
            ],
            [
                'name' => 'Bigo Live',
                'description' => 'Top up Bigo Beans - Official partner Bigo Live Indonesia',
                'category' => 'Live Streaming',
                'image' => 'services/bigo.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.50,
                'whatsapp_number' => '6281234567891',
            ],
            [
                'name' => 'Sugo Live',
                'description' => 'Top up Sugo Coins - Termurah dan terpercaya',
                'category' => 'Live Streaming',
                'image' => 'services/sugo.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.25,
                'whatsapp_number' => '6281234567892',
            ],
            [
                'name' => 'Papaya Live',
                'description' => 'Top up Papaya Coins - Proses instan 24 jam',
                'category' => 'Live Streaming',
                'image' => 'services/papaya.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.30,
                'whatsapp_number' => '6281234567893',
            ],
            [
                'name' => 'Azal Live',
                'description' => 'Top up Azal Coins - Platform live streaming populer',
                'category' => 'Live Streaming',
                'image' => 'services/azal.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.35,
                'whatsapp_number' => '6281234567810',
            ],
            [
                'name' => 'Layla Live',
                'description' => 'Top up Layla Coins - Live streaming komunitas',
                'category' => 'Live Streaming',
                'image' => 'services/layla.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.40,
                'whatsapp_number' => '6281234567811',
            ],
            [
                'name' => 'Lita Live',
                'description' => 'Top up Lita Coins - Platform hiburan live',
                'category' => 'Live Streaming',
                'image' => 'services/lita.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.20,
                'whatsapp_number' => '6281234567812',
            ],
            [
                'name' => 'Kitty Live',
                'description' => 'Top up Kitty Coins - Live streaming interaktif',
                'category' => 'Live Streaming',
                'image' => 'services/kitty.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.45,
                'whatsapp_number' => '6281234567813',
            ],
            [
                'name' => 'Bunda Live',
                'description' => 'Top up Bunda Coins - Komunitas live streaming Indonesia',
                'category' => 'Live Streaming',
                'image' => 'services/bunda.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.30,
                'whatsapp_number' => '6281234567814',
            ],
            [
                'name' => 'China Live',
                'description' => 'Top up China Coins - Platform live streaming Asia',
                'category' => 'Live Streaming',
                'image' => 'services/china.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.25,
                'whatsapp_number' => '6281234567815',
            ],
            [
                'name' => 'Golden Live',
                'description' => 'Top up Golden Coins - Premium live streaming',
                'category' => 'Live Streaming',
                'image' => 'services/golden.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.50,
                'whatsapp_number' => '6281234567816',
            ],
            [
                'name' => 'Star Live',
                'description' => 'Top up Star Coins - Bintang live streaming',
                'category' => 'Live Streaming',
                'image' => 'services/star.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.35,
                'whatsapp_number' => '6281234567817',
            ],
            [
                'name' => 'Candy Live',
                'description' => 'Top up Candy Coins - Manisnya live streaming',
                'category' => 'Live Streaming',
                'image' => 'services/candy.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.40,
                'whatsapp_number' => '6281234567818',
            ],
            [
                'name' => 'Cute Live',
                'description' => 'Top up Cute Coins - Live streaming imut',
                'category' => 'Live Streaming',
                'image' => 'services/cute.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.30,
                'whatsapp_number' => '6281234567819',
            ],
            [
                'name' => 'Joy Live',
                'description' => 'Top up Joy Coins - Kegembiraan tanpa batas',
                'category' => 'Live Streaming',
                'image' => 'services/joy.png',
                'price' => 10000,
                'status' => 'active',
                'is_active' => true,
                'commission_rate' => 1.25,
                'whatsapp_number' => '6281234567820',
            ],
        ];

        // Backward-compat: jika dulu tersimpan sebagai "Yalla Live", rename ke "Layla Live" (tanpa bikin duplikat).
        $legacyYalla = Service::where('name', 'Yalla Live')->first();
        $existingLayla = Service::where('name', 'Layla Live')->first();
        if ($legacyYalla && !$existingLayla) {
            $legacyYalla->update([
                'name' => 'Layla Live',
                'description' => 'Top up Layla Coins - Live streaming komunitas',
                'image' => 'services/layla.png',
            ]);
        }

        // Insert services, skip if already exists
        foreach ($services as $service) {
            Service::firstOrCreate(
                ['name' => $service['name']],
                $service
            );
        }

        $this->command->info('✅ ServiceSeeder completed! ' . count($services) . ' services seeded.');
    }
}
