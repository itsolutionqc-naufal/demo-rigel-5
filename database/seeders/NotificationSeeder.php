<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get all non-admin users
        $users = User::where('role', '!=', 'admin')->get();

        if ($users->isEmpty()) {
            $this->command->info('No non-admin users found. Skipping notification seeding.');
            return;
        }

        $notifications = [
            [
                'title' => 'Selamat Datang di Rigel Agency!',
                'message' => 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!',
                'type' => 'success',
                'data' => ['type' => 'welcome'],
                'read_at' => null,
                'created_at' => now()->subDays(7),
            ],
            [
                'title' => 'Transaksi Berhasil',
                'message' => 'Transaksi #001 telah berhasil disetujui. Komisi sebesar Rp 50.000 telah ditambahkan ke saldo Anda.',
                'type' => 'success',
                'data' => [
                    'type' => 'transaction',
                    'transaction_id' => 1,
                    'amount' => 500000,
                    'commission_amount' => 50000
                ],
                'read_at' => now()->subDays(1),
                'created_at' => now()->subDays(3),
            ],
            [
                'title' => 'Artikel Baru Tersedia',
                'message' => 'Artikel baru "Tips Meningkatkan Penjualan" telah dipublikasikan. Baca sekarang untuk mendapatkan informasi terbaru!',
                'type' => 'info',
                'data' => [
                    'type' => 'article',
                    'article_id' => 1,
                    'article_title' => 'Tips Meningkatkan Penjualan'
                ],
                'read_at' => null,
                'created_at' => now()->subDays(2),
            ],
            [
                'title' => 'Penarikan Komisi Berhasil',
                'message' => 'Penarikan komisi sebesar Rp 100.000 telah berhasil diproses dan akan segera masuk ke rekening Anda.',
                'type' => 'success',
                'data' => [
                    'type' => 'withdrawal',
                    'amount' => 100000,
                    'status' => 'success'
                ],
                'read_at' => now()->subHours(12),
                'created_at' => now()->subDays(1),
            ],
            [
                'title' => 'Transaksi Sedang Diproses',
                'message' => 'Transaksi #002 sedang diproses oleh admin. Mohon tunggu konfirmasi lebih lanjut.',
                'type' => 'info',
                'data' => [
                    'type' => 'transaction',
                    'transaction_id' => 2,
                    'amount' => 750000,
                    'status' => 'process'
                ],
                'read_at' => null,
                'created_at' => now()->subHours(6),
            ],
            [
                'title' => 'Sistem Maintenance',
                'message' => 'Sistem akan menjalani maintenance pada tanggal 10 Februari 2026 pukul 02:00 - 04:00 WIB. Mohon maaf atas ketidaknyamanannya.',
                'type' => 'warning',
                'data' => [
                    'type' => 'maintenance',
                    'scheduled_at' => '2026-02-10 02:00:00'
                ],
                'read_at' => null,
                'created_at' => now()->subHours(2),
            ]
        ];

        foreach ($users as $user) {
            foreach ($notifications as $notificationData) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => $notificationData['title'],
                    'message' => $notificationData['message'],
                    'type' => $notificationData['type'],
                    'data' => $notificationData['data'],
                    'read_at' => $notificationData['read_at'],
                    'created_at' => $notificationData['created_at'],
                    'updated_at' => $notificationData['created_at'],
                ]);
            }
        }

        $this->command->info('Notifications seeded successfully!');
    }
}