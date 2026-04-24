<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
            Riwayat Penjualan
        </h2>
        <a href="https://wa.me/{{ \App\Models\Setting::getWhatsAppNumber() }}?text={{ \App\Models\Setting::getWhatsAppMessageTemplate() }}"
           class="inline-flex items-center gap-2 rounded-full bg-green-500 px-3 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
            <span>Butuh bantuan?</span>
        </a>
    </div>

    {{-- Statistics Cards --}}
    @if(isset($totalTransactions))
        <div class="grid grid-cols-2 gap-3">
            <div class="relative rounded-xl bg-blue-50 p-4 dark:bg-blue-900/20">
                <div class="absolute right-3 top-3">
                    <i data-lucide="receipt" class="size-5 text-blue-400 dark:text-blue-500"></i>
                </div>
                <p class="text-xs font-medium text-blue-600 dark:text-blue-400">Total Transaksi</p>
                <p class="mt-1 text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $totalTransactions }}</p>
            </div>
            <div class="relative rounded-xl bg-emerald-50 p-4 dark:bg-emerald-900/20">
                <div class="absolute right-3 top-3">
                    <i data-lucide="check-circle" class="size-5 text-emerald-400 dark:text-emerald-500"></i>
                </div>
                <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Berhasil</p>
                <p class="mt-1 text-2xl font-bold text-emerald-700 dark:text-emerald-300">{{ $successTransactions }}</p>
            </div>
            <div class="relative rounded-xl bg-amber-50 p-4 dark:bg-amber-900/20">
                <div class="absolute right-3 top-3">
                    <i data-lucide="clock" class="size-5 text-amber-400 dark:text-amber-500"></i>
                </div>
                <p class="text-xs font-medium text-amber-600 dark:text-amber-400">Pending</p>
                <p class="mt-1 text-2xl font-bold text-amber-700 dark:text-amber-300">{{ $pendingTransactions }}</p>
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
        <form method="GET" action="{{ route('mobile.app', ['page' => 'history']) }}" class="space-y-3">
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
                <option value="pending" {{ ($currentStatus ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="process" {{ ($currentStatus ?? '') === 'process' ? 'selected' : '' }}>Diproses</option>
                <option value="success" {{ ($currentStatus ?? '') === 'success' ? 'selected' : '' }}>Berhasil</option>
                <option value="failed" {{ ($currentStatus ?? '') === 'failed' ? 'selected' : '' }}>Gagal</option>
            </select>
        </form>
    </div>

    @if(isset($transactions) && $transactions->count() > 0)
        <div class="space-y-4">
            @foreach($transactions as $transaction)
                <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-4 shadow-sm transition-all hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:bg-neutral-800/50">
                    <div class="mb-3 flex items-start justify-between">
                        <div>
                            <div class="text-sm font-semibold text-neutral-900 dark:text-white">
                                TRX {{ $transaction->created_at->timezone('Asia/Jakarta')->format('dmy') }}
                            </div>
                            @if($transaction->user)
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ $transaction->user->name }}
                                </div>
                                <div class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                                    Total Komisi: <span class="font-medium">Rp {{ number_format($transaction->commission_amount ?? 0, 0, ',', '.') }} / Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-neutral-900 dark:text-white">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </div>
                            @if($transaction->status === 'success')
                                <div class="text-xs font-medium text-emerald-500">
                                    Sukses
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-neutral-100 pt-3 dark:border-neutral-800">
                        <div class="text-xs text-neutral-400 dark:text-neutral-500">
                            {{ $transaction->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                        </div>

                        @php
                            $statusColors = [
                                'success' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                'pending' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                'failed' => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                            ];
                            $statusLabel = [
                                'success' => 'Berhasil',
                                'pending' => 'Diproses',
                                'failed' => 'Gagal',
                            ];
                        @endphp

                        <div class="flex items-center gap-2">
                            <a href="https://wa.me/{{ \App\Models\Setting::getWhatsAppNumber() }}?text={{ urlencode('Halo kak, mau nanya soal status transaksi saya dengan ID ' . $transaction->id) }}"
                               target="_blank"
                               class="flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-600 transition hover:bg-green-100 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-3.5">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                <span>WhatsApp</span>
                            </a>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-gray-50 text-gray-700' }}">
                                {{ $statusLabel[$transaction->status] ?? ucfirst($transaction->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
            <div class="mt-6 flex items-center justify-center gap-2">
                {{-- Previous Button --}}
                @if($transactions->onFirstPage())
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg border border-neutral-300 bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-800 cursor-not-allowed opacity-50">
                        <i data-lucide="chevron-left" class="size-5 text-neutral-400 dark:text-neutral-600"></i>
                    </div>
                @else
                    <a href="{{ $transactions->appends(request()->query())->previousPageUrl() }}" 
                       class="flex items-center justify-center w-10 h-10 rounded-lg border border-neutral-300 hover:bg-neutral-100 dark:border-neutral-600 dark:hover:bg-neutral-700 transition-colors">
                        <i data-lucide="chevron-left" class="size-5 text-neutral-600 dark:text-neutral-400"></i>
                    </a>
                @endif

                {{-- Page Numbers --}}
                <div class="flex items-center gap-1">
                    @foreach(range(1, $transactions->lastPage()) as $page)
                        @if($page === $transactions->currentPage())
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-600 text-white font-semibold text-sm">
                                {{ $page }}
                            </div>
                        @else
                            <a href="{{ $transactions->appends(request()->query())->url($page) }}" 
                               class="flex items-center justify-center w-10 h-10 rounded-lg border border-neutral-300 hover:bg-neutral-100 dark:border-neutral-600 dark:hover:bg-neutral-700 transition-colors text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                </div>

                {{-- Next Button --}}
                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->appends(request()->query())->nextPageUrl() }}" 
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
                Belum Ada Penjualan
            </h3>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Riwayat penjualan Anda kosong.
            </p>
        </div>
    @endif
</div>
