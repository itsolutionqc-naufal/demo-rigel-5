<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ArticleForm extends Component
{
    use WithFileUploads;

    public $title;
    public $slug;
    public $excerpt;
    public $content;
    public $image;
    public $article_category;
    public $article_status = 'draft';

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'article_category' => 'nullable|string|max:100',
        'article_status' => 'required|in:draft,published',
        'image' => 'nullable|image|max:10240', // Max 10MB
    ];

    protected $messages = [
        'title.required' => 'Judul artikel wajib diisi',
        'content.required' => 'Konten artikel wajib diisi',
        'image.image' => 'File harus berupa gambar',
        'image.max' => 'Ukuran gambar maksimal 10MB',
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
            // For new articles, we don't have ID yet, so use timestamp prefix
            $articleId = $this->article_id ?? time();
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

    private function uploadArticleImage($file)
    {
        try {
            // Create upload directory if it doesn't exist
            $uploadDir = public_path('upload/articles');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate filename: {timestamp}-{sanitized-title}.{ext}
            $titleSlug = $this->title ? Str::slug($this->title) : 'artikel';
            $timestamp = now()->format('Y-m-d-H-i');
            $extension = $file->getClientOriginalExtension();
            $filename = "{$timestamp}-{$titleSlug}.{$extension}";

            // Move file to public/upload/articles/
            $file->move($uploadDir, $filename);

            // Return URL path
            return "/upload/articles/{$filename}";
        } catch (\Exception $e) {
            // Return null on error, or you could throw the exception
            return null;
        }
    }

    public function save()
    {
        $this->article_status = 'published';
        return $this->saveArticle();
    }

    public function saveDraft()
    {
        $this->article_status = 'draft';
        return $this->saveArticle();
    }

    private function saveArticle()
    {
        try {
            // Clean content before validation
            if ($this->content) {
                $textContent = strip_tags($this->content);
                $textContent = trim($textContent);
                if (empty($textContent)) {
                    $this->content = ''; // Change null to empty string
                }
            }

            $this->validate();

            // Handle image upload
            $imagePath = null;
            if ($this->image) {
                if (is_string($this->image)) {
                    // Image is already a URL/path
                    $imagePath = $this->image;
                } else {
                    // Upload new image
                    $imagePath = $this->uploadArticleImage($this->image);
                }
            }

            // Ensure user is authenticated
            $userId = auth()->id();
            if (!$userId) {
                throw new \Exception('User not authenticated. Please log in to create an article.');
            }

            $article = Article::create([
                'title' => $this->title ?: 'Tanpa Judul',
                'slug' => Str::slug($this->title ?: 'tanpa-judul-' . time()),
                'excerpt' => $this->excerpt,
                'content' => $this->content,
                'category' => $this->article_category,
                'status' => $this->article_status,
                'image' => $imagePath,
                'user_id' => $userId,
                'published_at' => $this->article_status === 'published' ? now() : null,
            ]);

            $message = $this->article_status === 'published'
                ? 'Artikel berhasil dipublikasikan!'
                : 'Artikel berhasil disimpan sebagai draft!';

            session()->flash('success', $message);

            // For Livewire, use redirect to route
            $this->redirect(route('articles'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions to show errors
            throw $e;
        } catch (\Exception $e) {
            // Log error for debugging
            logger()->error('Article save error: ' . $e->getMessage());

            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.article-form');
    }
}
