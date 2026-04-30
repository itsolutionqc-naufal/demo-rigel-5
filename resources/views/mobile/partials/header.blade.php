<header class="sticky top-0 z-20 flex items-center justify-between border-b border-neutral-100 bg-white/80 px-4 py-3 backdrop-blur-md dark:border-neutral-800 dark:bg-neutral-950/80">
    <div class="flex items-center gap-2">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto dark:brightness-0 dark:invert" />
    </div>
    <div class="flex items-center gap-3">
        <a href="/app/notification" class="text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
            <i data-lucide="bell" class="size-5"></i>
        </a>
        <a href="/app/profile" class="h-8 w-8 overflow-hidden rounded-full border-2 border-white dark:border-neutral-700 shadow-md transition hover:opacity-80 bg-neutral-100 dark:bg-neutral-800">
            @if(auth()->user()->avatar && file_exists(public_path(auth()->user()->avatar)))
                <img src="{{ asset(auth()->user()->avatar) }}"
                     alt="Avatar"
                     class="h-full w-full object-cover"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=000&color=fff'">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=000&color=fff"
                     alt="Avatar"
                     class="h-full w-full object-cover" />
            @endif
        </a>
    </div>
</header>
