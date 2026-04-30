	<x-layouts::app :title="__('Laporan Penjualan & Komisi')">
	    <?php
	    // Helper function to abbreviate large numbers
	    if (! function_exists('formatAbbreviate')) {
	        function formatAbbreviate($number)
	        {
	            if ($number >= 1000000000) {
	                return number_format($number / 1000000000, 2) . 'M';
	            } elseif ($number >= 1000000) {
	                return number_format($number / 1000000, 2) . 'Jt';
	            } elseif ($number >= 1000) {
	                return number_format($number / 1000, 2) . 'rb';
	            }

	            return number_format($number, 0, ',', '.');
	        }
	    }
	    ?>
        @php
            $routePrefix = auth()->user()->isMarketing() ? 'marketing.' : '';
        @endphp
    <div class="container mx-auto px-4 py-6 space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Laporan Penjualan & Komisi
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Laporan total penjualan dan komisi harian/mingguan/bulanan
                </p>
            </div>
        </div>

	        <!-- Period Selector -->
	        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm p-6">
	            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
	                <div class="grid grid-cols-3 gap-2 w-full md:w-auto">
	                    <a href="{{ route($routePrefix.'reports.sales', ['period' => 'daily', 'date' => now()->format('Y-m-d')]) }}"
	                       class="w-full text-center px-3 py-2 rounded-lg font-medium text-sm transition-colors
	                              {{ request('period', 'daily') === 'daily' ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600' }}">
	                        Harian
	                    </a>
	                    <a href="{{ route($routePrefix.'reports.sales', ['period' => 'weekly', 'week' => now()->format('o-\\WW')]) }}"
	                       class="w-full text-center px-3 py-2 rounded-lg font-medium text-sm transition-colors
	                              {{ request('period') === 'weekly' ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600' }}">
	                        Mingguan
	                    </a>
	                    <a href="{{ route($routePrefix.'reports.sales', ['period' => 'monthly', 'date' => now()->format('Y-m-01')]) }}"
	                       class="w-full text-center px-3 py-2 rounded-lg font-medium text-sm transition-colors
	                              {{ request('period') === 'monthly' ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600' }}">
	                        Bulanan
	                    </a>
	                </div>

	                <form method="GET" class="flex flex-col md:flex-row md:items-end gap-3 w-full md:w-auto">
	                    <input type="hidden" name="period" value="{{ request('period', 'daily') }}">

	                    @if(request('period') === 'monthly')
	                        <!-- Date Range Filter for Monthly -->
	                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full">
	                            <div class="flex flex-col gap-1 w-full">
	                                <label for="start_date" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Dari</label>
	                                <input
	                                    type="date"
	                                    name="start_date"
	                                    value="{{ request('start_date') }}"
	                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500"
	                                >
	                            </div>
	                            <div class="flex flex-col gap-1 w-full">
	                                <label for="end_date" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Sampai</label>
	                                <input
	                                    type="date"
	                                    name="end_date"
	                                    value="{{ request('end_date') }}"
	                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500"
	                                >
	                            </div>
	                        </div>
	                    @elseif(request('period') === 'weekly')
	                        <!-- Week Filter -->
	                        <div class="flex flex-col gap-1 w-full md:w-auto">
	                            <label for="week" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
	                                Minggu
	                            </label>
	                            <input
	                                type="week"
	                                name="week"
	                                value="{{ request('week', now()->format('o-\\WW')) }}"
	                                class="w-full md:w-auto rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500"
	                            >
	                        </div>
	                    @else
	                        <!-- Single Date Filter for Daily -->
	                        <div class="flex flex-col gap-1 w-full md:w-auto">
	                            <label for="date" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
	                                Tanggal
	                            </label>
	                            <input
	                                type="date"
	                                name="date"
	                                value="{{ request('date', now()->format('Y-m-d')) }}"
	                                class="w-full md:w-auto rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500"
	                            >
	                        </div>
	                    @endif

	                    <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
	                        <button type="submit" class="w-full sm:w-auto justify-center inline-flex items-center gap-2 rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
	                            <i data-lucide="refresh-cw" class="size-4"></i>
	                            <span>Filter</span>
	                        </button>

		                        <a
		                            href="{{ route($routePrefix.'reports.sales.export', array_merge(['format' => 'xlsx'], request()->query())) }}"
		                            class="w-full sm:w-auto justify-center inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600"
		                        >
		                            <svg class="size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M128 128C128 92.7 156.7 64 192 64L341.5 64C358.5 64 374.8 70.7 386.8 82.7L493.3 189.3C505.3 201.3 512 217.6 512 234.6L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 128zM336 122.5L336 216C336 229.3 346.7 240 360 240L453.5 240L336 122.5zM292 330.7C284.6 319.7 269.7 316.7 258.7 324C247.7 331.3 244.7 346.3 252 357.3L291.2 416L252 474.7C244.6 485.7 247.6 500.6 258.7 508C269.8 515.4 284.6 512.4 292 501.3L320 459.3L348 501.3C355.4 512.3 370.3 515.3 381.3 508C392.3 500.7 395.3 485.7 388 474.7L348.8 416L388 357.3C395.4 346.3 392.4 331.4 381.3 324C370.2 316.6 355.4 319.6 348 330.7L320 372.7L292 330.7z"/></svg>
		                            <span>Export Excel</span>
		                        </a>

		                        <a
		                            href="{{ route($routePrefix.'reports.sales.export', array_merge(['format' => 'pdf'], request()->query())) }}"
		                            class="w-full sm:w-auto justify-center inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600"
		                        >
		                            <svg class="size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M128 64C92.7 64 64 92.7 64 128L64 512C64 547.3 92.7 576 128 576L208 576L208 464C208 428.7 236.7 400 272 400L448 400L448 234.5C448 217.5 441.3 201.2 429.3 189.2L322.7 82.7C310.7 70.7 294.5 64 277.5 64L128 64zM389.5 240L296 240C282.7 240 272 229.3 272 216L272 122.5L389.5 240zM272 444C261 444 252 453 252 464L252 592C252 603 261 612 272 612C283 612 292 603 292 592L292 564L304 564C337.1 564 364 537.1 364 504C364 470.9 337.1 444 304 444L272 444zM304 524L292 524L292 484L304 484C315 484 324 493 324 504C324 515 315 524 304 524zM400 444C389 444 380 453 380 464L380 592C380 603 389 612 400 612L432 612C460.7 612 484 588.7 484 560L484 496C484 467.3 460.7 444 432 444L400 444zM420 572L420 484L432 484C438.6 484 444 489.4 444 496L444 560C444 566.6 438.6 572 432 572L420 572zM508 464L508 592C508 603 517 612 528 612C539 612 548 603 548 592L548 548L576 548C587 548 596 539 596 528C596 517 587 508 576 508L548 508L548 484L576 484C587 484 596 475 596 464C596 453 587 444 576 444L528 444C517 444 508 453 508 464z"/></svg>
		                            <span>Export PDF</span>
		                        </a>
	                    </div>
	                </form>
	            </div>
	        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Sales -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-800 dark:to-neutral-900 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Penjualan</p>
                        <p class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">
                            @if($summary && $summary->total_sales)
                                @php $sales = $summary->total_sales; @endphp
                                @if($sales >= 1000000000)
                                    Rp {{ formatAbbreviate($sales) }}
                                @elseif($sales >= 1000000)
                                    Rp {{ formatAbbreviate($sales) }}
                                @else
                                    Rp {{ number_format($sales, 0, ',', '.') }}
                                @endif
                            @else
                                Rp 0
                            @endif
                        </p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                <i data-lucide="trending-up" class="size-3"></i>
                                @if($summary && $summary->total_sales)
                                    {{ round(($summary->total_sales / max(1, $summary->total_sales)) * 100, 2) }}%
                                @else
                                    0%
                                @endif
                            </span>
                            <span class="text-neutral-500 dark:text-neutral-400 ml-1">dari periode sebelumnya</span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-neutral-300 dark:bg-neutral-600 p-3">
                        <i data-lucide="shopping-cart" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Commission -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/30 dark:to-emerald-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Total Komisi</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-900 dark:text-emerald-100">
                            @if($summary && $summary->total_commission)
                                Rp {{ number_format($summary->total_commission, 0, ',', '.') }}
                            @else
                                Rp 0
                            @endif
                        </p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                <i data-lucide="trending-up" class="size-3"></i>
                                @if($summary && $summary->total_commission)
                                    {{ round(($summary->total_commission / max(1, $summary->total_commission)) * 100, 2) }}%
                                @else
                                    0%
                                @endif
                            </span>
                            <span class="text-neutral-500 dark:text-neutral-400 ml-1">dari periode sebelumnya</span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-emerald-400 dark:bg-emerald-600 p-3">
                        <i data-lucide="hand-coins" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Transactions -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/30 dark:to-amber-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-700 dark:text-amber-300">Total Transaksi</p>
                        <p class="mt-2 text-3xl font-bold text-amber-900 dark:text-amber-100">
                            @if($summary && $summary->total_transactions)
                                {{ number_format($summary->total_transactions) }}
                            @else
                                0
                            @endif
                        </p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400">
                                <i data-lucide="file" class="size-3"></i>
                                @if($summary && $summary->total_transactions)
                                    {{ $summary->total_transactions }} transaksi
                                @else
                                    0 transaksi
                                @endif
                            </span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-amber-400 dark:bg-amber-600 p-3">
                        <i data-lucide="receipt-text" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Card 4: Average Commission Rate -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Rata-rata Komisi</p>
                        <p class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100">
    @if($summary && $summary->total_sales > 0 && $summary->total_commission)
        {{ number_format(($summary->total_commission / $summary->total_sales) * 100, 2) }}%
    @else
        0.00%
    @endif
</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                <i data-lucide="percent" class="size-3"></i>
                                rata-rata
                            </span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-blue-400 dark:bg-blue-600 p-3">
                        <i data-lucide="bar-chart-3" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

	        @if(auth()->user()->isAdmin())
	        <!-- Bank Account & QRIS Section -->
	        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
	            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
	                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
	                    Informasi Pembayaran
                </h2>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Daftar nomor rekening dan QRIS untuk pembayaran
                </p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Bank Accounts -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 flex items-center gap-2">
                            <i data-lucide="building-2" class="size-4"></i>
                            Rekening Bank
                        </h3>

                        @php
                            $bankMethods = $paymentMethods->where('type', 'bank_account');
                        @endphp

                        @forelse($bankMethods as $method)
                            <div class="flex items-start gap-4 p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg 
                                    @if(str_contains(strtolower($method->name), 'bca'))
                                        bg-blue-100 dark:bg-blue-900/30
                                    @elseif(str_contains(strtolower($method->name), 'mandiri'))
                                        bg-yellow-100 dark:bg-yellow-900/30
                                    @elseif(str_contains(strtolower($method->name), 'bri'))
                                        bg-blue-100 dark:bg-blue-900/30
                                    @elseif(str_contains(strtolower($method->name), 'bni'))
                                        bg-orange-100 dark:bg-orange-900/30
                                    @else
                                        bg-neutral-100 dark:bg-neutral-900/30
                                    @endif
                                    flex items-center justify-center">
                                    <span class="text-xs font-bold 
                                        @if(str_contains(strtolower($method->name), 'bca'))
                                            text-blue-700 dark:text-blue-300
                                        @elseif(str_contains(strtolower($method->name), 'mandiri'))
                                            text-yellow-700 dark:text-yellow-300
                                        @elseif(str_contains(strtolower($method->name), 'bri'))
                                            text-blue-700 dark:text-blue-300
                                        @elseif(str_contains(strtolower($method->name), 'bni'))
                                            text-orange-700 dark:text-orange-300
                                        @else
                                            text-neutral-700 dark:text-neutral-300
                                        @endif">
                                        @if(str_contains(strtolower($method->name), 'bca'))
                                            BCA
                                        @elseif(str_contains(strtolower($method->name), 'mandiri'))
                                            MANDIRI
                                        @elseif(str_contains(strtolower($method->name), 'bri'))
                                            BRI
                                        @elseif(str_contains(strtolower($method->name), 'bni'))
                                            BNI
                                        @else
                                            {{ strtoupper(substr($method->name, 0, 3)) }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $method->name }}</p>
                                    <p class="text-lg font-bold text-neutral-900 dark:text-white mt-1">{{ $method->account_number }}</p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">a.n. {{ $method->account_holder }}</p>
                                </div>
                                <button onclick="copyToClipboard('{{ str_replace('-', '', $method->account_number) }}')" class="flex-shrink-0 p-2 rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition">
                                    <i data-lucide="copy" class="size-4 text-neutral-600 dark:text-neutral-400"></i>
                                </button>
                            </div>
                        @empty
                            <div class="p-6 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-center">
                                <i data-lucide="building-2" class="size-12 text-neutral-400 dark:text-neutral-600 mx-auto mb-3"></i>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Belum ada rekening bank yang aktif</p>
                                <p class="text-xs text-neutral-400 dark:text-neutral-500 mt-1">Tambahkan rekening bank melalui menu Payment Methods</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- QRIS -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 flex items-center gap-2">
                            <i data-lucide="qr-code" class="size-4"></i>
                            QRIS Code
                        </h3>

                        @php
                            $qrisMethods = $paymentMethods->where('type', 'qris');
                            $qrisMethod = $qrisMethods->first();
                        @endphp

                        @if($qrisMethod)
                            <div class="p-6 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700">
                                <div class="flex flex-col items-center">
                                    <!-- QR Code Display -->
                                    <div class="w-64 h-64 bg-white dark:bg-neutral-800 rounded-lg border-2 border-dashed border-neutral-300 dark:border-neutral-600 flex items-center justify-center mb-4">
                                        @if($qrisMethod->qr_code_path && file_exists(public_path($qrisMethod->qr_code_path)))
                                            <img src="{{ asset($qrisMethod->qr_code_path) }}" alt="QRIS Code" class="w-full h-full object-contain rounded-lg">
                                        @else
                                            <!-- QRIS Placeholder -->
                                            <div class="text-center p-6">
                                                <div class="w-32 h-32 mx-auto mb-4 bg-neutral-200 dark:bg-neutral-700 rounded-lg flex items-center justify-center">
                                                    <svg class="w-20 h-20 text-neutral-500 dark:text-neutral-400" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm10 0h2v2h-2v-2zm0 4h2v2h-2v-2zm4-4h2v2h-2v-2zm0 4h2v2h-2v-2zm-6-6h2v2h-2v-2zm2-2h2v2h-2v-2zm2 2h2v2h-2v-2z"/>
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">QRIS Code</p>
                                                <p class="text-xs text-neutral-500 dark:text-neutral-500 mt-1">Scan untuk pembayaran</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="text-center">
                                        @if($qrisMethod->nmid)
                                            <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                                NMID: {{ $qrisMethod->nmid }}
                                            </p>
                                        @endif
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                            Scan QRIS menggunakan aplikasi e-wallet atau mobile banking
                                        </p>
                                    </div>

                                    @if($qrisMethod->qr_code_path && file_exists(public_path($qrisMethod->qr_code_path)))
                                        <a href="{{ asset($qrisMethod->qr_code_path) }}" download="qris-code.png" class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-neutral-900 text-white text-sm font-medium hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200 transition">
                                            <i data-lucide="download" class="size-4"></i>
                                            <span>Download QRIS</span>
                                        </a>
                                    @else
                                        <button onclick="alert('QRIS code belum tersedia')" class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-neutral-400 text-white text-sm font-medium cursor-not-allowed">
                                            <i data-lucide="download" class="size-4"></i>
                                            <span>Download QRIS</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="p-6 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-center">
                                <i data-lucide="qr-code" class="size-12 text-neutral-400 dark:text-neutral-600 mx-auto mb-3"></i>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">QRIS belum tersedia</p>
                                <p class="text-xs text-neutral-400 dark:text-neutral-500 mt-1">Hubungi administrator untuk mengaktifkan QRIS</p>
                            </div>
                        @endif
                    </div>
                </div>
	            </div>
	        </div>
	        @endif

	        <!-- Charts Section -->
	        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm p-6">
	            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-6">
                Grafik Penjualan & Komisi
            </h2>

            <!-- Chart Container -->
            <div class="h-80">
                <canvas id="salesCommissionChart"></canvas>
            </div>
        </div>

        <!-- Data Table -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    @php
                        $p = request('period', 'daily');
                        $label = $p === 'daily' ? 'Harian' : ($p === 'weekly' ? 'Mingguan' : 'Bulanan');
                    @endphp
                    Rincian Data {{ $label }}
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                {{ request('period', 'daily') === 'daily' ? 'Jam' : (request('start_date') && request('end_date') ? 'Tanggal' : 'Hari') }}
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Jumlah Penjualan
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Jumlah Komisi
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Jumlah Transaksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @if(request('period', 'daily') === 'daily')
                            @for($i = 0; $i < 24; $i++)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-neutral-900 dark:text-white">{{ $i }}:00</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                            Rp {{ number_format(collect($chartSalesData)->get($i, 0), 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                            Rp {{ number_format(collect($chartCommissionData)->get($i, 0), 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                            {{ number_format(collect($chartTransactionsData)->get($i, 0)) }}
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        @else
                            @foreach($chartLabels as $index => $label)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-neutral-900 dark:text-white">{{ $label }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                            Rp {{ number_format(collect($chartSalesData)->get($index, 0), 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                            Rp {{ number_format(collect($chartCommissionData)->get($index, 0), 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                            {{ number_format(collect($chartTransactionsData)->get($index, 0)) }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Copy to clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show alert
                alert('Nomor rekening berhasil disalin: ' + text);
            }, function(err) {
                console.error('Gagal menyalin: ', err);
            });
        }

        // Initialize Lucide icons
        function initLucideIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function ensureChartJsLoaded(callback) {
            if (typeof window.Chart !== 'undefined') {
                callback();
                return;
            }

            if (window.__salesReportChartJsLoading) {
                window.__salesReportChartJsLoading.then(callback);
                return;
            }

            window.__salesReportChartJsLoading = new Promise(function(resolve) {
                const existing = document.querySelector('script[data-sales-report-chartjs="1"]');
                if (existing && typeof window.Chart !== 'undefined') {
                    resolve();
                    return;
                }

                if (existing) {
                    existing.addEventListener('load', resolve, { once: true });
                    existing.addEventListener('error', resolve, { once: true });
                    return;
                }

                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                script.async = true;
                script.dataset.salesReportChartjs = '1';
                script.onload = resolve;
                script.onerror = resolve;
                document.head.appendChild(script);
            });

            window.__salesReportChartJsLoading.then(callback);
        }

        function initSalesReportChart() {
            const canvas = document.getElementById('salesCommissionChart');
            if (!canvas) return;

            if (window.salesCommissionChart && typeof window.salesCommissionChart.destroy === 'function') {
                window.salesCommissionChart.destroy();
                window.salesCommissionChart = null;
            }

            const ctx = canvas.getContext('2d');

            window.salesCommissionChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: 'Penjualan',
                            data: @json($chartSalesData),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Komisi',
                            data: @json($chartCommissionData),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#6b7280',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                color: '#6b7280'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                color: '#6b7280',
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        function initSalesReportPage() {
            initLucideIcons();
            setTimeout(initLucideIcons, 50);
            ensureChartJsLoaded(function () {
                if (typeof window.Chart === 'undefined') return;
                initSalesReportChart();
            });
        }

        document.addEventListener('DOMContentLoaded', initSalesReportPage);
        document.addEventListener('livewire:navigated', initSalesReportPage);
    </script>
</x-layouts::app>
