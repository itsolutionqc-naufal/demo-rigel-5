<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class CleanupArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:cleanup {--keywords=*} {--categories=*} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up articles based on keywords or categories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting article cleanup process...');

        $keywords = $this->option('keywords');
        $categories = $this->option('categories');
        $force = $this->option('force');

        $query = Article::query();
        $deletedCount = 0;

        // If no specific options provided, clean up top-up related articles
        if (empty($keywords) && empty($categories)) {
            $defaultKeywords = [
                'Voya', 'Sugo', 'top up', 'topup', 'koin', 'coin', 'diamond', 'UC',
                'Genesis Crystal', 'Oneiric Shard', 'Bigo Live', 'TikTok', 'Mobile Legends',
                'PUBG', 'Genshin Impact', 'Honkai Star Rail', 'Free Fire', 'game', 'gaming',
                'promo', 'cashback', 'voucher', 'diskon'
            ];

            $query->where(function ($q) use ($defaultKeywords) {
                foreach ($defaultKeywords as $keyword) {
                    $q->orWhere('title', 'like', '%' . $keyword . '%')
                      ->orWhere('content', 'like', '%' . $keyword . '%')
                      ->orWhere('excerpt', 'like', '%' . $keyword . '%');
                }
            });

            $count = $query->count();
            
            if ($count > 0) {
                if (!$force && !$this->confirm("Found {$count} articles with top-up keywords. Delete them?")) {
                    $this->info('Operation cancelled.');
                    return;
                }

                $deletedCount = $query->delete();
                $this->info("Successfully deleted {$deletedCount} articles with top-up keywords.");
            } else {
                $this->info('No articles found with top-up keywords.');
            }
        } else {
            // Custom cleanup based on provided options
            if (!empty($keywords)) {
                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $q->orWhere('title', 'like', '%' . $keyword . '%')
                          ->orWhere('content', 'like', '%' . $keyword . '%')
                          ->orWhere('excerpt', 'like', '%' . $keyword . '%');
                    }
                });
            }

            if (!empty($categories)) {
                $query->whereIn('category', $categories);
            }

            $count = $query->count();
            
            if ($count > 0) {
                if (!$force && !$this->confirm("Found {$count} articles matching criteria. Delete them?")) {
                    $this->info('Operation cancelled.');
                    return;
                }

                $deletedCount = $query->delete();
                $this->info("Successfully deleted {$deletedCount} articles.");
            } else {
                $this->info('No articles found matching criteria.');
            }
        }

        $this->info('Cleanup process completed.');
    }
}