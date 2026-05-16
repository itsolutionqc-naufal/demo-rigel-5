<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function create()
    {
        return view('articles-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|max:10240', // Max 10MB
            'excerpt' => 'nullable|string|max:500',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $uploadDir = public_path('uploads/articles');
            if (! file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $filename = time() . '_' . $image->getClientOriginalName();

            // Move file to public/uploads/articles
            $image->move($uploadDir, $filename);

            // Store relative path
            $imagePath = 'uploads/articles/' . $filename;
        }

        $article = \App\Models\Article::create([
            'title' => $request->title,
            'slug' => \Illuminate\Support\Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category' => $request->category,
            'status' => $request->status,
            'image' => $imagePath,
            'user_id' => auth()->id(),
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil disimpan!',
            'article' => $article
        ], 201);
    }

    public function show(\App\Models\Article $article)
    {
        return view('articles.show', compact('article'));
    }

    public function edit(\App\Models\Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, \App\Models\Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|max:10240', // Max 10MB
            'excerpt' => 'nullable|string|max:500',
        ]);

        $imagePath = $article->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($article->image && file_exists(public_path($article->image))) {
                unlink(public_path($article->image));
            }
            
            $image = $request->file('image');

            $uploadDir = public_path('uploads/articles');
            if (! file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate unique filename
            $filename = time() . '_' . $image->getClientOriginalName();
            
            // Move file to public/uploads/articles
            $image->move($uploadDir, $filename);
            
            // Store relative path
            $imagePath = 'uploads/articles/' . $filename;
        }

        $article->update([
            'title' => $request->title,
            'slug' => \Illuminate\Support\Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category' => $request->category,
            'status' => $request->status,
            'image' => $imagePath,
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('articles')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(\App\Models\Article $article)
    {
        // Delete image file if exists
        if ($article->image && file_exists(public_path($article->image))) {
            unlink(public_path($article->image));
        }
        
        $article->delete();

        return response()->json(['success' => true, 'message' => 'Artikel berhasil dihapus!']);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Validate file
            $request->validate([
                'file' => 'required|image|max:10240', // Max 10MB
            ]);

            $uploadDir = public_path('uploads/articles');
            if (! file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Move file to public/uploads/articles
            $file->move($uploadDir, $filename);

            // Return the URL
            $url = asset('uploads/articles/' . $filename);
            return response()->json([
                'location' => $url,
                'url' => $url,
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
