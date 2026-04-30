<x-layouts::app :title="__('Daftar Pesanan')">
    @php
        $routePrefix = auth()->user()->isMarketing() ? 'marketing.' : '';
    @endphp
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    {{ __('Daftar Pesanan') }}
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Kelola semua pesanan dalam sistem
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 mb-6 dark:bg-green-900/20 dark:border-green-800">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                ID
                            </th>
	                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
	                                Nama Pelanggan
	                            </th>
	                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
	                                Nominal
	                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Komisi
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Petugas
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Dibuat
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">
                                    {{ $sale->id }}
	                                </td>
	                                <td class="px-6 py-4 whitespace-nowrap">
	                                    @php
	                                        $customerLabel = $sale->user?->username
	                                            ?: ($sale->user?->name ?: ($sale->customer_name ?: '-'));
	                                    @endphp
	                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">{{ $customerLabel }}</div>
	                                </td>
	                                <td class="px-6 py-4 whitespace-nowrap text-center">
	                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                        Rp {{ number_format($sale->amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                        Rp {{ number_format($sale->commission_amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($sale->status === 'process')
                                            bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-200
                                        @elseif($sale->status === 'success')
                                            bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200
                                        @else
                                            bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-200
                                        @endif">
                                        {{ $sale->status === 'success' ? 'Sukses' : ($sale->status === 'failed' ? 'Gagal' : 'Proses') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-900 dark:text-white">{{ $sale->user->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $sale->created_at->format('d/m/Y H:i') }}
                                </td>
	                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
	                                    <div class="flex justify-center space-x-3">
	                                        <a href="{{ route($routePrefix.'sales.show', $sale) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Lihat Detail">
	                                            <i data-lucide="eye" class="size-4"></i>
	                                        </a>
	                                        @if(auth()->user()->isAdmin())
	                                            <a href="{{ route($routePrefix.'sales.edit', $sale) }}" class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300" title="Edit">
	                                                <i data-lucide="edit" class="size-4"></i>
	                                            </a>
	                                            @if($sale->status === 'process')
	                                                <form method="POST" action="{{ route($routePrefix.'sales.approve', $sale) }}" class="inline">
	                                                    @csrf
	                                                    <button type="submit" class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300" title="Setujui">
                                                        <i data-lucide="check-circle" class="size-4"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route($routePrefix.'sales.reject', $sale) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Tolak">
                                                        <i data-lucide="x-circle" class="size-4"></i>
                                                    </button>
                                                </form>
                                            @elseif($sale->status === 'success')
                                                <form method="POST" action="{{ route($routePrefix.'sales.process', $sale) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300" title="Kembalikan ke Proses">
                                                        <i data-lucide="refresh-cw" class="size-4"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route($routePrefix.'sales.reject', $sale) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Tolak">
                                                        <i data-lucide="x-circle" class="size-4"></i>
                                                    </button>
                                                </form>
                                            @elseif($sale->status === 'failed')
                                                <form method="POST" action="{{ route($routePrefix.'sales.approve', $sale) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300" title="Setujui">
                                                        <i data-lucide="check-circle" class="size-4"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route($routePrefix.'sales.process', $sale) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300" title="Kembalikan ke Proses">
                                                        <i data-lucide="refresh-cw" class="size-4"></i>
	                                                    </button>
	                                                </form>
	                                            @endif
	                                        @endif
	                                    </div>
	                                </td>
                            </tr>
	                        @empty
	                            <tr>
	                                <td colspan="8" class="px-6 py-12 text-center">
	                                    <div class="flex flex-col items-center justify-center">
	                                        <i data-lucide="package" class="size-16 text-neutral-300 dark:text-neutral-700 mb-4"></i>
	                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">
	                                            Tidak ada data pesanan
                                        </p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                            Pesanan yang telah dibuat akan muncul di sini
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sales->hasPages())
                <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>

</x-layouts::app>
