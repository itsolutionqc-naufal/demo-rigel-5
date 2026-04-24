<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
                Riwayat Submit Host
            </h2>
            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                Status seleksi host akan diupdate secara berkala
            </p>
        </div>
        <a href="{{ route('mobile.app', ['page' => 'hunter']) }}"
           class="inline-flex items-center gap-1 rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-sm font-medium text-neutral-700 shadow-sm transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800">
            <i data-lucide="chevron-left" class="size-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Statistics Cards --}}
    @if(isset($totalSubmissions))
        <div class="grid grid-cols-2 gap-3">
            <div class="relative rounded-xl bg-blue-50 p-4 dark:bg-blue-900/20">
                <div class="absolute right-3 top-3">
                    <i data-lucide="receipt" class="size-5 text-blue-400 dark:text-blue-500"></i>
                </div>
                <p class="text-xs font-medium text-blue-600 dark:text-blue-400">Total Submit Host</p>
                <p class="mt-1 text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $totalSubmissions }}</p>
            </div>
            <div class="relative rounded-xl bg-emerald-50 p-4 dark:bg-emerald-900/20">
                <div class="absolute right-3 top-3">
                    <i data-lucide="check-circle" class="size-5 text-emerald-400 dark:text-emerald-500"></i>
                </div>
                <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Lolos</p>
                <p class="mt-1 text-2xl font-bold text-emerald-700 dark:text-emerald-300">{{ $passedSubmissions ?? 0 }}</p>
            </div>
            <div class="relative rounded-xl bg-red-50 p-4 dark:bg-red-900/20">
                <div class="absolute right-3 top-3">
                    <i data-lucide="x-circle" class="size-5 text-red-400 dark:text-red-500"></i>
                </div>
                <p class="text-xs font-medium text-red-600 dark:text-red-400">Tidak Lolos</p>
                <p class="mt-1 text-2xl font-bold text-red-700 dark:text-red-300">{{ $notPassedSubmissions ?? 0 }}</p>
            </div>
            <div class="relative rounded-xl bg-purple-50 p-4 dark:bg-purple-900/20">
                <div class="absolute right-3 top-3">
                    <i data-lucide="wallet" class="size-5 text-purple-400 dark:text-purple-500"></i>
                </div>
                <p class="text-xs font-medium text-purple-600 dark:text-purple-400">Total Komisi</p>
                <p class="mt-1 text-lg font-bold text-purple-700 dark:text-purple-300">Rp {{ number_format($totalCommission ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
    @endif

    {{-- Filter --}}
    <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
        <form method="GET" action="{{ route('mobile.app', ['page' => 'host-history']) }}" class="space-y-3">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="filter" class="size-4 text-neutral-600 dark:text-neutral-400"></i>
                <h4 class="text-sm font-semibold text-neutral-900 dark:text-white">Filter Status</h4>
            </div>

            <select
                name="status"
                onchange="this.form.submit()"
                class="w-full rounded-lg border border-neutral-300 bg-neutral-50 px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white dark:focus:border-white dark:focus:ring-white"
            >
                <option value="all" {{ ($currentStatus ?? 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="process" {{ ($currentStatus ?? '') === 'process' ? 'selected' : '' }}>Seleksi</option>
                <option value="success" {{ ($currentStatus ?? '') === 'success' ? 'selected' : '' }}>Lolos</option>
                <option value="failed" {{ ($currentStatus ?? '') === 'failed' ? 'selected' : '' }}>Tidak Lolos</option>
            </select>
        </form>
    </div>

    @php
        $statusColors = [
            'success' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
            'process' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
            'failed' => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
        ];
        $statusLabel = [
            'success' => 'Lolos',
            'process' => 'Seleksi',
            'failed' => 'Tidak Lolos',
        ];
    @endphp

    @if(isset($submissions) && $submissions->count() > 0)
        <div class="space-y-4">
            @foreach($submissions as $submission)
                @php
                    $transactionStatus = $submission->saleTransaction?->status;
                @endphp
                <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-4 shadow-sm transition-all hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:bg-neutral-800/50">
                    <div class="mb-3 flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="truncate text-sm font-semibold text-neutral-900 dark:text-white">
                                {{ ($submission->host_id ?: '-') }} - {{ ($submission->nickname ?: '-') }}
                            </div>
                            @php
                                $serviceName = $submission->service?->name ?? $submission->saleTransaction?->service_name;
                                $serviceImage = $submission->service?->image;
                            @endphp

                            @if($serviceName)
                                <div class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                                    <span class="inline-flex items-center gap-2">
                                        @if($serviceImage)
                                            <img
                                                src="{{ asset($serviceImage) }}"
                                                alt="{{ $serviceName }}"
                                                class="size-5 rounded-md object-cover"
                                                loading="lazy"
                                            />
                                        @endif
                                        <span>
                                            Aplikasi: <span class="font-medium">{{ $serviceName }}</span>
                                        </span>
                                    </span>
                                </div>
                            @endif
                            <div class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                                No. WhatsApp: <span class="font-medium">{{ $submission->whatsapp_number ?: '-' }}</span>
                            </div>
                            <div class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                                Formulir: <span class="font-medium">{{ $submission->form_filled ? 'Sudah' : 'Belum' }}</span>
                            </div>
                        </div>

                        <span class="shrink-0 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$transactionStatus] ?? 'bg-gray-50 text-gray-700' }}">
                            {{ $statusLabel[$transactionStatus] ?? ucfirst((string) $transactionStatus) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between border-t border-neutral-100 pt-3 dark:border-neutral-800">
                        <div class="text-xs text-neutral-400 dark:text-neutral-500">
                            {{ ($submission->saleTransaction?->created_at ?? $submission->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                        </div>

                        @if($submission->whatsapp_number)
                            <a href="https://wa.me/{{ $submission->whatsapp_number }}"
                               target="_blank"
                               class="flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-600 transition hover:bg-green-100 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50">
                                <i data-lucide="message-circle" class="size-3.5"></i>
                                <span>WhatsApp</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($submissions->hasPages())
            <div class="mt-6 flex items-center justify-center gap-2">
                @if($submissions->onFirstPage())
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg border border-neutral-300 bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-800 cursor-not-allowed opacity-50">
                        <i data-lucide="chevron-left" class="size-5 text-neutral-400 dark:text-neutral-600"></i>
                    </div>
                @else
                    <a href="{{ $submissions->appends(request()->query())->previousPageUrl() }}"
                       class="flex items-center justify-center w-10 h-10 rounded-lg border border-neutral-300 hover:bg-neutral-100 dark:border-neutral-600 dark:hover:bg-neutral-700 transition-colors">
                        <i data-lucide="chevron-left" class="size-5 text-neutral-600 dark:text-neutral-400"></i>
                    </a>
                @endif

                <div class="flex items-center gap-1">
                    @foreach(range(1, $submissions->lastPage()) as $page)
                        @if($page === $submissions->currentPage())
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-600 text-white font-semibold text-sm">
                                {{ $page }}
                            </div>
                        @else
                            <a href="{{ $submissions->appends(request()->query())->url($page) }}"
                               class="flex items-center justify-center w-10 h-10 rounded-lg border border-neutral-300 hover:bg-neutral-100 dark:border-neutral-600 dark:hover:bg-neutral-700 transition-colors text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                </div>

                @if($submissions->hasMorePages())
                    <a href="{{ $submissions->appends(request()->query())->nextPageUrl() }}"
                       class="flex items-center justify-center w-10 h-10 rounded-lg border border-neutral-300 hover:bg-neutral-100 dark:border-neutral-600 dark:hover:bg-neutral-700 transition-colors">
                        <i data-lucide="chevron-right" class="size-5 text-neutral-600 dark:text-neutral-400"></i>
                    </a>
                @else
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg border border-neutral-300 bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-800 cursor-not-allowed opacity-50">
                        <i data-lucide="chevron-right" class="size-5 text-neutral-400 dark:text-neutral-600"></i>
                    </div>
                @endif
            </div>
        @endif
    @else
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <i data-lucide="receipt" class="size-12 text-neutral-300 dark:text-neutral-700 mx-auto mb-3"></i>
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">
                Belum Ada Submit Host
            </h3>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Riwayat submit host Anda kosong.
            </p>
        </div>
    @endif
</div>
