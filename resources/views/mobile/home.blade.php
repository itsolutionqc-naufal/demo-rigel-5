<x-layouts::mobile title="Mobile Home">
    <div class="flex flex-col flex-1 p-4">
        @if(view()->exists("mobile.pages.{$page}"))
            @include("mobile.pages.{$page}")
        @else
            <div class="flex h-full flex-col items-center justify-center text-center p-4">
                <i data-lucide="file-warning" class="size-16 text-neutral-300 dark:text-neutral-700 mb-4"></i>
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Coming Soon</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Halaman "{{ ucfirst($page) }}" belum tersedia.</p>
            </div>
        @endif
    </div>
</x-layouts::mobile>
