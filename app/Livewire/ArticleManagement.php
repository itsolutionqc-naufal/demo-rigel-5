<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class ArticleManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $category = '';
    public $status = '';
    public $start_date = '';
    public $end_date = '';

    // Checkbox
    public $selectedItems = [];
    public $selectAll = false;
    public $confirmDelete = false;
    public $deleteType = 'selected'; // 'selected' or 'all'

    // Edit mode
    public $title;
    public $slug;
    public $excerpt;
    public $content;
    public $image;
    public $article_id;
    public $article_category;
    public $article_status = 'draft';

    public $isEditMode = false;
    public $showModal = false;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'article_category' => 'nullable|string|max:100',
        'article_status' => 'required|in:draft,published',
    ];

    protected $messages = [
        'title.required' => 'Judul artikel wajib diisi',
        'content.required' => 'Konten artikel wajib diisi',
    ];

    public function uploadImage($file)
    {
        try {
            // Create upload directory if it doesn't exist
            $uploadDir = public_path('upload/articles');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate filename: {id}-{sanitized-title}-{timestamp}.ext
            $articleId = $this->article_id ?? 'new';
            $titleSlug = $this->title ? Str::slug($this->title) : 'artikel';
            $timestamp = now()->format('Y-m-d-H-i');
            $extension = $file->getClientOriginalExtension();
            $filename = "{$articleId}-{$titleSlug}-{$timestamp}.{$extension}";

            // Move file to public/upload/articles/
            $file->move($uploadDir, $filename);

            // Return URL (accessible from web)
            $url = "/upload/articles/{$filename}";

            return [
                'url' => $url,
                'location' => $url
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function render()
    {
        // Cache expensive queries for better performance
        $cacheKey = 'articles_query_' . md5(json_encode([
            'search' => $this->search,
            'category' => $this->category,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'page' => $this->getPage(),
        ]));

        $articles = cache()->remember($cacheKey, 300, function () {
            return $this->filteredArticlesQuery()
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        });

        // Cache categories query
        $categories = cache()->remember('article_categories', 3600, function () {
            return Article::whereNotNull('category')
                ->where('category', '!=', '')
                ->select('category')
                ->distinct()
                ->pluck('category')
                ->sort()
                ->values();
        });

        return view('livewire.article-management', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    private function filteredArticlesQuery(): Builder
    {
        return Article::query()
            ->when($this->search, function (Builder $query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function (Builder $query) {
                $query->where('category', $this->category);
            })
            ->when($this->status, function (Builder $query) {
                $query->where('status', $this->status);
            })
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('created_at', '<=', $this->end_date);
            });
    }

    /**
     * Get article IDs on the current page (with current filters applied).
     *
     * @return array<int, string>
     */
    private function currentPageArticleIds(): array
    {
        $page = (int) ($this->getPage() ?: 1);
        $page = max(1, $page);

        return $this->filteredArticlesQuery()
            ->orderBy('created_at', 'desc')
            ->forPage($page, 10)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->values()
            ->toArray();
    }

    /**
     * Reset all filters
     */
    public function resetFilters()
    {
        $this->reset(['search', 'category', 'status', 'start_date', 'end_date']);
        $this->resetPage();
        $this->deselectAll();
    }

    /**
     * Select all items on current page
     */
    public function selectAllOnPage()
    {
        $this->selectedItems = $this->currentPageArticleIds();
        $this->selectAll = true;
    }

    /**
     * Deselect all items
     */
    public function deselectAll()
    {
        $this->selectedItems = [];
        $this->selectAll = false;
    }

    /**
     * Sync selection when "select all" checkbox is toggled.
     */
    public function updatedSelectAll($value): void
    {
        $this->selectedItems = collect($this->selectedItems)
            ->map(fn ($id) => (string) $id)
            ->values()
            ->toArray();

        $currentPageIds = $this->currentPageArticleIds();

        if ((bool) $value) {
            $this->selectedItems = collect(array_merge($this->selectedItems, $currentPageIds))
                ->unique()
                ->values()
                ->toArray();
            return;
        }

        $this->selectedItems = array_values(array_diff($this->selectedItems, $currentPageIds));
    }

    /**
     * Sync "select all" state when any item checkbox changes.
     */
    public function updatedSelectedItems(): void
    {
        $this->selectedItems = collect($this->selectedItems)
            ->map(fn ($id) => (string) $id)
            ->values()
            ->toArray();

        $currentPageIds = $this->currentPageArticleIds();
        if (count($currentPageIds) === 0) {
            $this->selectAll = false;
            return;
        }

        $selectedOnCurrentPage = array_intersect($currentPageIds, $this->selectedItems);
        $this->selectAll = count($selectedOnCurrentPage) === count($currentPageIds);
    }

    /**
     * Delete selected articles
     */
    public function deleteSelected()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Pilih artikel yang akan dihapus!');
            return;
        }

        $count = count($this->selectedItems);
        Article::whereIn('id', $this->selectedItems)->delete();
        
        $this->selectedItems = [];
        $this->selectAll = false;
        $this->confirmDelete = false;
        
        session()->flash('success', "{$count} artikel berhasil dihapus!");
    }

    /**
     * Delete all articles
     */
    public function deleteAll()
    {
        $count = Article::count();
        Article::query()->delete();
        
        $this->selectedItems = [];
        $this->selectAll = false;
        $this->confirmDelete = false;
        
        session()->flash('success', "Semua {$count} artikel berhasil dihapus!");
    }

    /**
     * Show confirmation for deleting selected articles
     */
    public function confirmDeleteSelected()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Pilih artikel yang akan dihapus!');
            return;
        }
        
        $this->deleteType = 'selected';
        $this->confirmDelete = true;
    }

    /**
     * Show confirmation for deleting all articles
     */
    public function confirmDeleteAll()
    {
        $this->deleteType = 'all';
        $this->confirmDelete = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['title', 'slug', 'excerpt', 'content', 'image', 'article_id', 'article_category', 'article_status']);
        $this->isEditMode = false;
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);

        $this->article_id = $article->id;
        $this->title = $article->title;
        $this->slug = $article->slug;
        $this->excerpt = $article->excerpt;
        $this->content = $article->content;
        $this->article_category = $article->category;
        $this->article_status = $article->status;

        $this->isEditMode = true;
        $this->showModal = true;
        
        // Dispatch event to reinitialize Quill editor
        $this->dispatch('modal-opened');
    }

    public function update()
    {
        // Clean content before validation
        if ($this->content) {
            $textContent = strip_tags($this->content);
            $textContent = trim($textContent);
            if (empty($textContent)) {
                $this->content = null;
            }
        }

        try {
            $this->validate();

            $article = Article::findOrFail($this->article_id);

            $article->update([
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'excerpt' => $this->excerpt,
                'content' => $this->content,
                'category' => $this->article_category,
                'status' => $this->article_status,
                'published_at' => $this->article_status === 'published' && !$article->published_at ? now() : $article->published_at,
            ]);

            $this->closeModal();
            session()->flash('success', 'Artikel berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions to show errors
            throw $e;
        } catch (\Exception $e) {
            // Log error for debugging
            logger()->error('Article update error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->article_id = $id;
        $this->confirmDelete = true;
    }

    public function delete()
    {
        $article = Article::findOrFail($this->article_id);
        $article->delete();

        // Clear cache after deletion
        cache()->forget('article_categories');
        cache()->tags(['articles'])->flush();

        $this->resetForm();
        $this->confirmDelete = false;
        session()->flash('success', 'Artikel berhasil dihapus!');
    }

    public function cancelDelete()
    {
        $this->confirmDelete = false;
        $this->article_id = null;
    }

    #[On('statusChanged')]
    public function toggleStatus($id)
    {
        $article = Article::findOrFail($id);
        $article->update([
            'status' => $article->status === 'published' ? 'draft' : 'published',
            'published_at' => $article->status === 'draft' ? now() : null,
        ]);

        session()->flash('success', 'Status artikel berhasil diubah!');
    }
}
