<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleTruncateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This will truncate (empty) the articles table completely
     */
    public function run(): void
    {
        $this->command->info('Checking articles table...');
        
        // Count articles before deletion
        $count = DB::table('articles')->count();
        $this->command->info("Found {$count} articles in database.");
        
        if ($count > 0) {
            // Show some sample articles that will be deleted
            $sampleArticles = DB::table('articles')->limit(5)->get(['id', 'title', 'category']);
            
            $this->command->info('Sample articles that will be deleted:');
            foreach ($sampleArticles as $article) {
                $this->command->info("- [{$article->id}] {$article->title} ({$article->category})");
            }
            
            if ($this->command->confirm('Do you want to delete ALL articles? This action cannot be undone.')) {
                // Delete all articles
                DB::table('articles')->truncate();
                $this->command->info('Successfully deleted all articles.');
                $this->command->info('Articles table is now empty.');
            } else {
                $this->command->info('Deletion cancelled.');
            }
        } else {
            $this->command->info('No articles found in database.');
        }
    }
}