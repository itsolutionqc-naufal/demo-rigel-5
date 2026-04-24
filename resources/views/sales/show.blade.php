<x-layouts::app.sidebar :title="'Detail Pesanan'">
    @php
        $routePrefix = auth()->user()->isMarketing() ? 'marketing.' : '';
    @endphp
    <flux:main>
        <div class="container mx-auto px-4 py-6">
            <div class="max-w-4xl mx-auto">
                <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="flex items-center justify-between p-6 border-b border-neutral-200 dark:border-neutral-800">
                        <h1 class="text-xl font-bold text-neutral-900 dark:text-white">Detail Pesanan #{{ $saleTransaction->id }}</h1>
                        <a href="{{ route($routePrefix.'sales.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                            <i data-lucide="arrow-left" class="size-4"></i>
                            Kembali
                        </a>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Nama Pelanggan</p>
                                    <p class="font-semibold text-neutral-900 dark:text-white">{{ $saleTransaction->customer_name ?? '-' }}</p>
                                </div>
                                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Nomor Telepon</p>
                                    <p class="font-semibold text-neutral-900 dark:text-white">{{ $saleTransaction->customer_phone ?: '-' }}</p>
                                </div>
                                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Nominal Pesanan</p>
                                    <p class="font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($saleTransaction->amount, 0, ',', '.') }}</p>
                                </div>
                                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Persentase Komisi</p>
                                    <p class="font-semibold text-neutral-900 dark:text-white">{{ $saleTransaction->commission_rate }}%</p>
                                </div>
                                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Jumlah Komisi</p>
                                    <p class="font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($saleTransaction->commission_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Status</p>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $saleTransaction->status === 'success' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : ($saleTransaction->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400') }}">
                                        {{ $saleTransaction->status === 'success' ? 'Sukses' : ($saleTransaction->status === 'failed' ? 'Gagal' : 'Proses') }}
                                    </span>
                                </div>
                                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Petugas</p>
                                    <p class="font-semibold text-neutral-900 dark:text-white">{{ $saleTransaction->user->name }}</p>
                                </div>
                                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Admin</p>
                                    <p class="font-semibold text-neutral-900 dark:text-white">{{ $saleTransaction->admin->name ?? '-' }}</p>
                                </div>
                                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Dibuat</p>
                                    <p class="font-semibold text-neutral-900 dark:text-white">{{ $saleTransaction->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Dikonfirmasi</p>
                                    <p class="font-semibold text-neutral-900 dark:text-white">{{ $saleTransaction->confirmed_at ? $saleTransaction->confirmed_at->format('d/m/Y H:i') : '-' }}</p>
                                </div>
                                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Selesai</p>
                                    <p class="font-semibold text-neutral-900 dark:text-white">{{ $saleTransaction->completed_at ? $saleTransaction->completed_at->format('d/m/Y H:i') : '-' }}</p>
                                </div>
                                <!-- Payment Method Information -->
                                @if($saleTransaction->paymentMethod)
                                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-800 col-span-2">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Metode Pembayaran</p>
                                    <div class="mt-2">
                                        <p class="font-semibold text-neutral-900 dark:text-white">{{ $saleTransaction->paymentMethod->name }}</p>
                                        @if($saleTransaction->paymentMethod->type === 'qris' && $saleTransaction->paymentMethod->qr_code_path)
                                            <div class="mt-3 flex flex-col items-center">
                                                <img
                                                    src="{{ asset($saleTransaction->paymentMethod->qr_code_path) }}"
                                                    alt="QRIS Code"
                                                    class="w-32 h-32 rounded border border-neutral-200 dark:border-neutral-700 bg-white p-2"
                                                >
                                                <p class="mt-2 text-xs text-neutral-600 dark:text-neutral-400">Kode QRIS</p>
                                            </div>
                                        @elseif($saleTransaction->paymentMethod->type === 'bank_account')
                                            <div class="mt-2 text-sm">
                                                <p><span class="font-medium">Nomor Rekening:</span> {{ $saleTransaction->paymentMethod->account_number }}</p>
                                                <p><span class="font-medium">Atas Nama:</span> {{ $saleTransaction->paymentMethod->account_holder }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
	                        </div>

	                        <div class="mt-6 flex flex-wrap gap-3">
	                            @if(auth()->user()->isAdmin())
	                                <a href="{{ route($routePrefix.'sales.edit', $saleTransaction) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
	                                    <i data-lucide="edit" class="size-4"></i>
	                                    Edit Pesanan
	                                </a>

	                                @if($saleTransaction->status === 'process')
	                                    <form method="POST" action="{{ route($routePrefix.'sales.approve', $saleTransaction) }}" class="inline">
	                                        @csrf
	                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-700">
                                            <i data-lucide="check" class="size-4"></i>
                                            Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route($routePrefix.'sales.reject', $saleTransaction) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                                            <i data-lucide="x" class="size-4"></i>
                                            Tolak
                                        </button>
                                    </form>
                                @elseif($saleTransaction->status === 'success')
                                    <form method="POST" action="{{ route($routePrefix.'sales.process', $saleTransaction) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-700">
                                            <i data-lucide="refresh-cw" class="size-4"></i>
                                            Kembalikan ke Proses
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route($routePrefix.'sales.reject', $saleTransaction) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                                            <i data-lucide="x" class="size-4"></i>
                                            Tolak
                                        </button>
                                    </form>
                                @elseif($saleTransaction->status === 'failed')
                                    <form method="POST" action="{{ route($routePrefix.'sales.approve', $saleTransaction) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-700">
                                            <i data-lucide="check" class="size-4"></i>
                                            Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route($routePrefix.'sales.process', $saleTransaction) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-700">
                                            <i data-lucide="refresh-cw" class="size-4"></i>
                                            Kembalikan ke Proses
	                                        </button>
	                                    </form>
	                                @endif
	                            @endif
	                        </div>
                    </div>
                </div>
            </div>
        </div>
    </flux:main>
</x-layouts::app.sidebar>
