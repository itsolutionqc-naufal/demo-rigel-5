<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';

    protected $paginationTheme = 'bootstrap';

    /**
     * Get custom pagination URL for mobile app route
     */
    public function getPaginationUrl($page)
    {
        return url('/app/artikel?page=' . $page);
    }

    public function render()
    {
        $articles = Article::query()
            ->published()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Get unique categories from database
        $categories = Article::whereNotNull('category')
            ->where('category', '!=', '')
            ->where('status', 'published')
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        // Set custom pagination path
        $articles->appends(request()->query());

        return view('livewire.article-list', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }
}
