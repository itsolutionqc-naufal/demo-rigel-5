<x-layouts::app :title="__('Marketing Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Marketing Dashboard
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Selamat datang, {{ ucfirst(auth()->user()->name) }}.
                </p>
            </div>

	            <div class="flex flex-col sm:flex-row sm:items-end gap-3 w-full lg:w-auto">
	                <form method="GET" action="{{ route('marketing.dashboard') }}" class="w-full">
	                    <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-end gap-3">
	                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full sm:w-auto">
	                            <div class="w-full">
	                                <label class="block text-xs text-neutral-500 dark:text-neutral-400 mb-1">Mulai</label>
	                                <input
	                                    type="date"
	                                    name="start_date"
	                                    value="{{ $startDate }}"
	                                    class="h-9 w-full sm:w-44 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-sm text-neutral-900 dark:text-white"
	                                />
	                            </div>
	                            <div class="w-full">
	                                <label class="block text-xs text-neutral-500 dark:text-neutral-400 mb-1">Sampai</label>
	                                <input
	                                    type="date"
	                                    name="end_date"
	                                    value="{{ $endDate }}"
	                                    class="h-9 w-full sm:w-44 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-sm text-neutral-900 dark:text-white"
	                                />
	                            </div>
	                        </div>
	
	                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
	                            <button
	                                type="submit"
	                                class="h-9 w-full sm:w-auto justify-center inline-flex items-center gap-2 px-4 rounded-lg bg-neutral-900 text-white text-sm font-medium hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200 transition-colors"
	                            >
	                                <i data-lucide="filter" class="size-4"></i>
	                                Terapkan
	                            </button>
	
	                            <a
	                                href="{{ route('marketing.dashboard') }}"
	                                class="h-9 w-full sm:w-auto justify-center inline-flex items-center gap-2 px-4 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-sm font-medium text-neutral-900 dark:text-white hover:bg-neutral-50 dark:hover:bg-neutral-700/40 transition-colors"
	                            >
	                                <i data-lucide="rotate-ccw" class="size-4"></i>
	                                Reset
	                            </a>
	                        </div>
	                    </div>
	                </form>
	
	                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
	                    @csrf
	                    <button
	                        type="submit"
	                        class="h-9 w-full sm:w-auto justify-center inline-flex items-center gap-2 px-4 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-sm font-medium text-neutral-900 dark:text-white hover:bg-neutral-50 dark:hover:bg-neutral-700/40 transition-colors"
	                    >
	                        <i data-lucide="log-out" class="size-4"></i>
	                        Logout
	                    </button>
	                </form>
	            </div>
	        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Penjualan</p>
                        <p class="mt-2 text-2xl font-bold text-neutral-900 dark:text-white truncate" title="Rp {{ number_format($totalSales, 0, ',', '.') }}">
                            Rp {{ number_format($totalSales, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Periode terpilih</p>
                    </div>
                    <div class="flex-shrink-0 rounded-lg bg-neutral-100 dark:bg-neutral-700 p-3">
                        <i data-lucide="shopping-cart" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Komisi</p>
                        <p class="mt-2 text-2xl font-bold text-neutral-900 dark:text-white truncate" title="Rp {{ number_format($totalCommissions, 0, ',', '.') }}">
                            Rp {{ number_format($totalCommissions, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Periode terpilih</p>
                    </div>
                    <div class="flex-shrink-0 rounded-lg bg-neutral-100 dark:bg-neutral-700 p-3">
                        <i data-lucide="dollar-sign" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Transaksi Sukses</p>
                        <p class="mt-2 text-2xl font-bold text-neutral-900 dark:text-white truncate">
                            {{ number_format($successfulTransactions, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Periode terpilih</p>
                    </div>
                    <div class="flex-shrink-0 rounded-lg bg-neutral-100 dark:bg-neutral-700 p-3">
                        <i data-lucide="check-circle" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Transaksi Gagal</p>
                        <p class="mt-2 text-2xl font-bold text-neutral-900 dark:text-white truncate">
                            {{ number_format($failedTransactions, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Periode terpilih</p>
                    </div>
                    <div class="flex-shrink-0 rounded-lg bg-neutral-100 dark:bg-neutral-700 p-3">
                        <i data-lucide="x-circle" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @php
                $hasTrendData = collect($trendSalesData ?? [])->sum() > 0 || collect($trendCommissionData ?? [])->sum() > 0;
            @endphp
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        Trend Penjualan & Komisi
                    </h2>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                        Berdasarkan transaksi sukses (non-wallet) pada periode terpilih
                    </p>
                </div>
                <div class="p-6">
                    @if($hasTrendData)
                        <div class="h-80">
                            <canvas id="trendChart"></canvas>
                        </div>
                    @else
                        <div class="h-80 flex items-center justify-center rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700">
                            <div class="text-center">
                                <i data-lucide="bar-chart-3" class="size-10 text-neutral-400 dark:text-neutral-600 mx-auto mb-2"></i>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Belum ada data pada periode ini</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        Performa User (Top 10)
                    </h2>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                        Total penjualan & komisi user yang Anda miliki
                    </p>
                </div>
                <div class="p-6">
                    @if(!empty($userLabels))
                        <div class="h-80">
                            <canvas id="userChart"></canvas>
                        </div>
                    @else
                        <div class="h-80 flex items-center justify-center rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700">
                            <div class="text-center">
                                <i data-lucide="users" class="size-10 text-neutral-400 dark:text-neutral-600 mx-auto mb-2"></i>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Belum ada data user / transaksi</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Daily Income Table -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    Total Penghasilan per Tanggal
                </h2>
                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                    Menampilkan penjualan, komisi, dan jumlah transaksi sukses (non-wallet)
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Total Penjualan
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Total Komisi
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Transaksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse(($dailyRows ?? []) as $row)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-neutral-700 dark:text-neutral-300">
                                    Rp {{ number_format((float) ($row['sales'] ?? 0), 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-neutral-700 dark:text-neutral-300">
                                    Rp {{ number_format((float) ($row['commission'] ?? 0), 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ number_format((int) ($row['transactions'] ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    Tidak ada data untuk ditampilkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Menu Cepat -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    Menu Cepat
                </h2>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <a href="{{ route('marketing.sales.index') }}" class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 hover:bg-neutral-50 dark:hover:bg-neutral-700/40 transition">
                        <div class="flex items-center gap-2 text-sm font-medium text-neutral-900 dark:text-white">
                            <i data-lucide="shopping-bag" class="size-4"></i>
                            <span>Penjualan</span>
                        </div>
                    </a>
                    <a href="{{ route('marketing.reports.sales') }}" class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 hover:bg-neutral-50 dark:hover:bg-neutral-700/40 transition">
                        <div class="flex items-center gap-2 text-sm font-medium text-neutral-900 dark:text-white">
                            <i data-lucide="chart-bar" class="size-4"></i>
                            <span>Laporan</span>
                        </div>
                    </a>
                    <a href="{{ route('marketing.wallet.index') }}" class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 hover:bg-neutral-50 dark:hover:bg-neutral-700/40 transition">
                        <div class="flex items-center gap-2 text-sm font-medium text-neutral-900 dark:text-white">
                            <i data-lucide="credit-card" class="size-4"></i>
                            <span>Wallet</span>
                        </div>
                    </a>
                    <a href="{{ route('marketing.transactions.index') }}" class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 hover:bg-neutral-50 dark:hover:bg-neutral-700/40 transition">
                        <div class="flex items-center gap-2 text-sm font-medium text-neutral-900 dark:text-white">
                            <i data-lucide="arrows-right-left" class="size-4"></i>
                            <span>Transaksi</span>
                        </div>
                    </a>
                    <a href="{{ route('marketing.users.index') }}" class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 hover:bg-neutral-50 dark:hover:bg-neutral-700/40 transition">
                        <div class="flex items-center gap-2 text-sm font-medium text-neutral-900 dark:text-white">
                            <i data-lucide="users" class="size-4"></i>
                            <span>Kelola User</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function rupiahTick(value) {
            try {
                return 'Rp ' + Number(value).toLocaleString('id-ID');
            } catch (e) {
                return 'Rp ' + value;
            }
        }

        function initLucideIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLucideIcons);
        } else {
            initLucideIcons();
        }

        setTimeout(initLucideIcons, 100);

        function initCharts() {
            // Trend chart
            const trendCanvas = document.getElementById('trendChart');
            if (trendCanvas) {
                const trendCtx = trendCanvas.getContext('2d');
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: @json($trendLabels ?? []),
                        datasets: [
                            {
                                label: 'Penjualan',
                                data: @json($trendSalesData ?? []),
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.12)',
                                borderWidth: 2,
                                tension: 0.35,
                                fill: true
                            },
                            {
                                label: 'Komisi',
                                data: @json($trendCommissionData ?? []),
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.12)',
                                borderWidth: 2,
                                tension: 0.35,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.dataset.label || '';
                                        return label + ': ' + rupiahTick(context.raw);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return rupiahTick(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Per-user chart
            const userCanvas = document.getElementById('userChart');
            if (userCanvas) {
                const userCtx = userCanvas.getContext('2d');
                new Chart(userCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($userLabels ?? []),
                        datasets: [
                            {
                                label: 'Penjualan',
                                data: @json($userSalesData ?? []),
                                backgroundColor: 'rgba(79, 70, 229, 0.25)',
                                borderColor: '#4f46e5',
                                borderWidth: 1
                            },
                            {
                                label: 'Komisi',
                                data: @json($userCommissionData ?? []),
                                backgroundColor: 'rgba(16, 185, 129, 0.25)',
                                borderColor: '#10b981',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.dataset.label || '';
                                        return label + ': ' + rupiahTick(context.raw);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return rupiahTick(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', initCharts);
        document.addEventListener('livewire:navigated', initCharts);
    </script>
</x-layouts::app>
