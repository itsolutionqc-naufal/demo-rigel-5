<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleCleanupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder will clean up all articles related to top up koin applications
     */
    public function run(): void
    {
        // Delete articles that contain keywords related to top up applications
        $keywords = [
            'Voya',
            'Sugo', 
            'top up',
            'topup',
            'koin',
            'coin',
            'diamond',
            'UC',
            'Genesis Crystal',
            'Oneiric Shard',
            'Bigo Live',
            'TikTok',
            'Mobile Legends',
            'PUBG',
            'Genshin Impact',
            'Honkai Star Rail',
            'Free Fire',
            'game',
            'gaming'
        ];

        $query = Article::query();
        
        foreach ($keywords as $keyword) {
            $query->orWhere('title', 'like', '%' . $keyword . '%')
                  ->orWhere('content', 'like', '%' . $keyword . '%')
                  ->orWhere('excerpt', 'like', '%' . $keyword . '%');
        }

        $count = $query->count();
        
        if ($count > 0) {
            $this->command->info("Found {$count} articles containing top up keywords.");
            $query->delete();
            $this->command->info("Successfully deleted {$count} articles.");
        } else {
            $this->command->info("No articles found with top up keywords.");
        }

        // Also delete any articles in specific categories
        $categoryCount = Article::whereIn('category', ['tutorial', 'review', 'tips', 'promo', 'news', 'faq'])->count();
        
        if ($categoryCount > 0) {
            $this->command->info("Found {$categoryCount} articles in top up categories.");
            Article::whereIn('category', ['tutorial', 'review', 'tips', 'promo', 'news', 'faq'])->delete();
            $this->command->info("Successfully deleted {$categoryCount} articles from categories.");
        }
    }
}