@props([
    'search' => '',
    'category' => '',
])

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-xl font-bold text-neutral-900 dark:text-white">Artikel & Berita</h2>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                Temukan artikel menarik dari Rigel Agency
            </p>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-2 mb-4">
        <flux:input type="text" wire:model.live="search" placeholder="Cari artikel..." />
        <flux:select wire:model.live="category">
            <option value="">Semua Kategori</option>
            @foreach($categories ?? [] as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </flux:select>
    </div>

    <!-- Articles Grid -->
    @if($articles->count() > 0)
        <div class="grid grid-cols-2 gap-3">
            @foreach($articles as $article)
                <a href="{{ route('mobile.app', ['page' => 'news-detail', 'id' => $article->id]) }}"
                   class="group block overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 hover:border-neutral-300 dark:hover:border-neutral-600 transition-all shadow-sm">
                    <!-- Image -->
                    @if($article->image)
                        <div class="aspect-video w-full bg-neutral-100 dark:bg-neutral-800">
                            <img src="{{ asset($article->image) }}"
                                 alt="{{ $article->title }}"
                                 class="h-full w-full object-cover transition group-hover:scale-105">
                        </div>
                    @else
                        <div class="aspect-video w-full bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center">
                            <i data-lucide="image" class="size-12 text-neutral-400 dark:text-neutral-500"></i>
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="p-3">
                        <!-- Category -->
                        @if($article->category)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 mb-2">
                                {{ $article->category }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400 mb-2">
                                Artikel
                            </span>
                        @endif

                        <!-- Title -->
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white line-clamp-2 mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                            {{ $article->title }}
                        </h3>

                        <!-- Excerpt -->
                        @if($article->excerpt)
                            <p class="text-xs text-neutral-600 dark:text-neutral-400 line-clamp-2 mb-2">
                                {{ $article->excerpt }}
                            </p>
                        @endif

                        <!-- Footer -->
                        <div class="flex items-center justify-between text-xs text-neutral-500 dark:text-neutral-400">
                            <div class="flex items-center gap-1">
                                <i data-lucide="calendar" class="size-3"></i>
                                <span class="text-xs">{{ $article->published_at ? $article->published_at->format('d M') : $article->created_at->format('d M') }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <i data-lucide="eye" class="size-3"></i>
                                <span class="text-xs">{{ $article->views }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($articles->hasPages())
            <div class="flex items-center justify-between gap-2 px-2">
                {{-- Previous Button --}}
                @if($articles->onFirstPage())
                    <span class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-neutral-400 dark:text-neutral-600 cursor-not-allowed">
                        <i data-lucide="chevron-left" class="size-3"></i>
                        <span>Prev</span>
                    </span>
                @else
                    <a href="{{ url('/app/artikel?page=' . $articles->currentPage() - 1) }}" wire:navigate class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                        <i data-lucide="chevron-left" class="size-3"></i>
                        <span>Prev</span>
                    </a>
                @endif

                {{-- Page Info --}}
                <span class="text-xs text-neutral-600 dark:text-neutral-400 font-medium">
                    {{ $articles->currentPage() }} / {{ $articles->lastPage() }}
                </span>

                {{-- Next Button --}}
                @if($articles->hasMorePages())
                    <a href="{{ url('/app/artikel?page=' . $articles->currentPage() + 1) }}" wire:navigate class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                        <span>Next</span>
                        <i data-lucide="chevron-right" class="size-3"></i>
                    </a>
                @else
                    <span class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-neutral-400 dark:text-neutral-600 cursor-not-allowed">
                        <span>Next</span>
                        <i data-lucide="chevron-right" class="size-3"></i>
                    </span>
                @endif
            </div>

            {{-- Result Info --}}
            <div class="text-center">
                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                    Showing {{ $articles->firstItem() }} to {{ $articles->lastItem() }} of {{ $articles->total() }} results
                </p>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-20 h-20 rounded-full bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center mb-4">
                <i data-lucide="file-x" class="size-10 text-neutral-400 dark:text-neutral-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">
                Belum ada artikel
            </h3>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Artikel belum tersedia saat ini.
            </p>
        </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
