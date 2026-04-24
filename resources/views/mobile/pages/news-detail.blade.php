@if(isset($error))
    <!-- Error State -->
    <div class="flex flex-col items-center justify-center py-12 text-center">
        <div class="w-20 h-20 rounded-full bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center mb-4">
            <i data-lucide="file-x" class="size-10 text-neutral-400 dark:text-neutral-600"></i>
        </div>
        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">
            Artikel Tidak Ditemukan
        </h3>
        <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-4">
            {{ $error }}
        </p>
        <a href="{{ route('mobile.app', ['page' => 'dashboard']) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
            <i data-lucide="home" class="size-4"></i>
            Kembali ke Beranda
        </a>
    </div>
@elseif(isset($article) && $article)
    <div class="flex flex-col gap-4 pb-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('mobile.app', ['page' => 'dashboard']) }}" class="inline-flex items-center gap-2 text-sm text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                <i data-lucide="arrow-left" class="size-4"></i>
                Kembali
            </a>
        </div>

        <!-- Article Image -->
        <div class="aspect-video w-full overflow-hidden rounded-xl bg-neutral-100 dark:bg-neutral-800">
            @if($article->image && file_exists(public_path($article->image)))
                <img src="{{ asset($article->image) }}" alt="{{ $article->title }}" class="h-full w-full object-cover">
            @else
                <div class="h-full w-full bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center">
                    <i data-lucide="image" class="size-12 text-neutral-400 dark:text-neutral-500"></i>
                </div>
            @endif
        </div>

        <!-- Article Header -->
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2">
                @if($article->category)
                    <span class="rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                        {{ $article->category }}
                    </span>
                @endif
                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                    {{ $article->published_at ? $article->published_at->diffForHumans() : $article->created_at->diffForHumans() }}
                </span>
            </div>
            <h1 class="text-2xl font-bold leading-tight text-neutral-900 dark:text-white">
                {{ $article->title }}
            </h1>
            @if($article->user)
                <div class="flex items-center gap-2 mt-1">
                    <div class="h-6 w-6 overflow-hidden rounded-full bg-neutral-200">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($article->user->name) }}"
                             alt="{{ $article->user->name }}"
                             class="h-full w-full object-cover">
                    </div>
                    <span class="text-sm text-neutral-600 dark:text-neutral-300">Oleh {{ $article->user->name }}</span>
                </div>
            @endif
        </div>

        <!-- Article Excerpt -->
        @if($article->excerpt)
            <div class="rounded-lg bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 p-4">
                <p class="text-sm text-indigo-900 dark:text-indigo-100 font-medium">
                    {{ $article->excerpt }}
                </p>
            </div>
        @endif

        <!-- Article Content -->
        <div class="prose prose-sm prose-neutral dark:prose-invert max-w-none">
            {!! $article->content !!}
        </div>

        <!-- Article Stats -->
        <div class="flex items-center justify-between pt-4 border-t border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center gap-4 text-xs text-neutral-500 dark:text-neutral-400">
                <div class="flex items-center gap-1">
                    <i data-lucide="eye" class="size-4"></i>
                    <span>{{ number_format($article->views ?? 0, 0, ',', '.') }} views</span>
                </div>
                <div class="flex items-center gap-1">
                    <i data-lucide="calendar" class="size-4"></i>
                    <span>{{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
                </div>
            </div>
            <a href="{{ route('mobile.app', ['page' => 'dashboard']) }}"
               class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                Baca Artikel Lainnya
                <i data-lucide="arrow-right" class="size-3"></i>
            </a>
        </div>
    </div>
@else
    <!-- Loading or No Article State -->
    <div class="flex flex-col items-center justify-center py-12 text-center">
        <div class="w-20 h-20 rounded-full bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center mb-4">
            <i data-lucide="loader-2" class="size-10 text-neutral-400 animate-spin"></i>
        </div>
        <p class="text-sm text-neutral-500 dark:text-neutral-400">Memuat artikel...</p>
    </div>
@endif
