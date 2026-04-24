<x-layouts::app :title="__('Detail Artikel')">
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Detail Artikel
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Melihat detail dari artikel "{{ $article->title }}"
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('articles') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors">
                    <i data-lucide="arrow-left" class="size-4"></i>
                    <span>Kembali</span>
                </a>
                <a href="{{ route('articles.edit', $article) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors">
                    <i data-lucide="pencil" class="size-4"></i>
                    <span>Edit</span>
                </a>
            </div>
        </div>

        <!-- Article Details Card -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
            <!-- Article Image -->
            @if($article->image)
                <div class="relative">
                    <img src="{{ asset($article->image) }}" alt="{{ $article->title }}" class="w-full h-64 object-cover">
                </div>
            @endif

            <!-- Article Content -->
            <div class="p-6">
                <!-- Title -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $article->title }}</h2>
                    <div class="mt-2 flex items-center gap-4 text-sm text-neutral-500 dark:text-neutral-400">
                        <span>Diterbitkan: {{ $article->published_at ? $article->published_at->format('d M Y H:i') : 'Belum diterbitkan' }}</span>
                        <span>•</span>
                        <span>Dibuat: {{ $article->created_at->format('d M Y H:i') }}</span>
                        <span>•</span>
                        <span>Penulis: {{ $article->user->name ?? 'Unknown' }}</span>
                    </div>
                </div>

                <!-- Category and Status -->
                <div class="flex items-center gap-4 mb-6">
                    @if($article->category)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                            {{ $article->category }}
                        </span>
                    @endif
                    
                    @if($article->status === 'published')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200">
                            <i data-lucide="check-circle" class="size-3"></i>
                            Published
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200">
                            <i data-lucide="file" class="size-3"></i>
                            Draft
                        </span>
                    @endif
                </div>

                <!-- Excerpt -->
                @if($article->excerpt)
                    <div class="mb-6 p-4 bg-neutral-50 dark:bg-neutral-700 rounded-lg border border-neutral-200 dark:border-neutral-600">
                        <h3 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Ringkasan</h3>
                        <p class="text-neutral-600 dark:text-neutral-400">{{ $article->excerpt }}</p>
                    </div>
                @endif

                <!-- Content -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Konten Artikel</h3>
                    <div class="prose dark:prose-invert max-w-none text-neutral-700 dark:text-neutral-300">
                        {!! $article->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>