<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
            {{ auth()->user()->role === 'user' ? 'Pilih Aplikasi' : 'Pilih Pekerjaan' }}
        </h2>
        <a href="/app/history" class="inline-flex items-center gap-1.5 rounded-lg border-2 border-amber-400/30 bg-amber-400/10 px-3 py-1.5 text-sm font-medium text-amber-600 transition hover:border-amber-400/50 hover:bg-amber-400/20 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:border-amber-500/50 dark:hover:bg-amber-500/20">
            <i data-lucide="history" class="size-4"></i>
            <span>Riwayat Penjualan</span>
        </a>
    </div>

    @if(isset($services) && $services->count() > 0)
        <div class="grid grid-cols-3 gap-3">
            @foreach($services as $service)
                <a href="/app/submit-data?service={{ $service->id }}"
                   class="group relative block w-full">
                    <div class="aspect-square w-full overflow-hidden rounded-lg p-2 mb-2">
                        @if($service->image)
                            <img src="{{ asset($service->image) }}"
                                 alt="{{ $service->name }}"
                                 class="h-full w-full object-cover rounded-lg transition duration-300 group-hover:scale-105">
                        @else
                            <div class="h-full w-full bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">{{ substr($service->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-neutral-900 dark:text-white text-center leading-tight">
                        {{ $service->name }}
                    </p>
                </a>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <i data-lucide="package" class="size-12 text-neutral-300 dark:text-neutral-700 mx-auto mb-3"></i>
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">
                Belum Ada Layanan
            </h3>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Layanan belum tersedia saat ini.
            </p>
        </div>
    @endif
</div>
