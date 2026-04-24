<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleDeleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder will delete articles created by ArticleSeeder
     */
    public function run(): void
    {
        // Get all articles that were created by the admin user and contain keywords from our seeder
        $articlesToDelete = Article::whereIn('title', [
            'Panduan Lengkap Top Up Koin Voya: Cara Mudah dan Aman',
            'Review Aplikasi Sugo: Top Up Game Tercepat dan Termurah',
            'Cara Top Up Diamond Mobile Legends di Voya dengan Promo',
            'Keuntungan Menggunakan Aplikasi Top Up Koin vs Konvensional',
            'Promo Top Up Koin Spesial: Cashback dan Bonus Diamond',
            'Cara Daftar dan Verifikasi Akun di Aplikasi Top Up Koin',
            'Perbandingan Harga Top Up Diamond ML di Voya vs Sugo',
            'Tips Aman Top Up Koin: Hindari Penipuan dan Scam',
            'Top Up Koin untuk Game Luar Negeri: Genshin Impact, Honkai Star Rail',
            'Event Spesial Ramadhan: Diskon Top Up Koin hingga 50%',
            'Cara Top Up UC PUBG Mobile di Aplikasi Sugo: Step by Step',
            'Keunggulan Aplikasi Voya: Kenapa Gamers Memilihnya?',
            'Top Up Koin untuk Aplikasi Live Streaming: Bigo Live, TikTok',
            'Cara Mendapatkan Voucher Diskon di Aplikasi Top Up Koin',
            'Perbandingan Fitur: Voya vs Sugo vs Aplikasi Lainnya',
            'Cara Klaim Garansi dan Refund di Aplikasi Top Up Koin',
            'Update Terbaru: Game Baru yang Support Top Up di Voya',
            'Strategi Bisnis Aplikasi Top Up Koin: Behind The Scene',
            'FAQ Aplikasi Top Up Koin: Jawaban untuk Pertanyaan Populer',
        ])->orWhere(function($query) {
            $query->where('category', 'tutorial')
                  ->orWhere('category', 'review')
                  ->orWhere('category', 'tips')
                  ->orWhere('category', 'promo')
                  ->orWhere('category', 'news')
                  ->orWhere('category', 'faq');
        })->where('status', 'published');

        $count = $articlesToDelete->count();
        
        if ($count > 0) {
            $this->command->info("Found {$count} articles to delete.");
            $articlesToDelete->delete();
            $this->command->info("Successfully deleted {$count} articles.");
        } else {
            $this->command->info("No articles found to delete.");
        }
    }
}