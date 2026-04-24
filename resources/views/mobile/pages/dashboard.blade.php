<div class="space-y-4 pb-4">
    <div class="flex items-center justify-between px-1">
        <h2 class="text-lg font-bold text-neutral-900 dark:text-white">Berita Terbaru</h2>
        <a href="{{ route('mobile.app', ['page' => 'artikel']) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Lihat Semua</a>
    </div>

    @if(isset($articles) && $articles->count() > 0)
        <div class="grid grid-cols-2 gap-3">
            @foreach($articles as $article)
                <a href="{{ route('mobile.app', ['page' => 'news-detail', 'id' => $article->id]) }}" class="group overflow-hidden rounded-xl border border-neutral-100 bg-white shadow-sm transition dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="aspect-video w-full bg-neutral-100 dark:bg-neutral-800 overflow-hidden">
                        @if($article->image)
                            <img src="{{ asset($article->image) }}" 
                                 alt="{{ $article->title }}" 
                                 loading="lazy"
                                 class="h-full w-full object-cover transition group-hover:scale-105">
                        @else
                            <div class="h-full w-full bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center">
                                <i data-lucide="image" class="size-8 text-neutral-400 dark:text-neutral-500"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <span class="text-[10px] font-medium text-indigo-600 dark:text-indigo-400">
                            {{ $article->category ?? 'Artikel' }}
                        </span>
                        <h3 class="mt-1 line-clamp-2 text-xs font-semibold text-neutral-900 dark:text-white">
                            {{ $article->title }}
                        </h3>
                        <p class="mt-1 text-[10px] text-neutral-500 dark:text-neutral-400">
                            {{ $article->created_at->diffForHumans() }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-12 px-4">
            <i data-lucide="inbox" class="size-12 text-neutral-300 dark:text-neutral-700 mb-3"></i>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 text-center">Belum ada artikel tersedia</p>
        </div>
    @endif
</div>
