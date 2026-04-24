<x-layouts::app :title="__('Manajemen Wallet')">
    @php
        $isMarketing = auth()->user()->isMarketing();
        $routePrefix = $isMarketing ? 'marketing.' : '';
    @endphp
    <div class="container mx-auto px-4 py-6 space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Manajemen Transaksi
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Kelola permintaan top-up dan penarikan yang menunggu persetujuan
                </p>
            </div>
        </div>

	        <!-- Tab Navigation -->
	        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 rounded-xl bg-neutral-100 p-1 dark:bg-neutral-800">
	            <a href="{{ route($routePrefix.'wallet.index', ['type' => 'topup']) }}" 
	               class="w-full rounded-lg px-4 py-2 text-sm font-medium text-center transition-colors
	                      {{ $type === 'topup' ? 'bg-white text-neutral-900 shadow-sm dark:bg-neutral-700 dark:text-white' : 'text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white' }}">
	                <i data-lucide="smartphone" class="size-4 inline mr-2"></i>
	                Permintaan Top-up
	            </a>
	            <a href="{{ $isMarketing ? route('marketing.withdraw.index') : route('wallet.index', ['type' => 'withdrawal']) }}" 
	               class="w-full rounded-lg px-4 py-2 text-sm font-medium text-center transition-colors
	                      {{ $type === 'withdrawal' ? 'bg-white text-neutral-900 shadow-sm dark:bg-neutral-700 dark:text-white' : 'text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white' }}">
	                <i data-lucide="credit-card" class="size-4 inline mr-2"></i>
	                Permintaan Penarikan
	            </a>
	        </div>

	        <!-- Date Filter -->
	        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-4 shadow-sm">
	            <form method="GET" action="{{ $isMarketing && $type === 'withdrawal' ? route('marketing.withdraw.index') : route($routePrefix.'wallet.index') }}" class="flex flex-col sm:flex-row items-start sm:items-end gap-4">
	                <input type="hidden" name="type" value="{{ $type }}">
                
                <div class="flex-1 w-full sm:w-auto">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        <i data-lucide="calendar" class="size-4 inline mr-1"></i>
                        Tanggal Mulai
                    </label>
                    <input
                        type="date"
                        name="start_date"
                        value="{{ request('start_date') }}"
                        class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    />
                </div>

                <div class="flex-1 w-full sm:w-auto">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        <i data-lucide="calendar" class="size-4 inline mr-1"></i>
                        Tanggal Akhir
                    </label>
                    <input
                        type="date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                        class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    />
                </div>

	                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
	                    <button
	                        type="submit"
	                        class="w-full sm:w-auto justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors inline-flex items-center gap-2"
	                    >
	                        <i data-lucide="filter" class="size-4"></i>
	                        Filter
	                    </button>
	                    
	                    @if(request('start_date') || request('end_date'))
	                        <a
	                            href="{{ $isMarketing ? ($type === 'withdrawal' ? route('marketing.withdraw.index') : route('marketing.wallet.index', ['type' => $type])) : route('wallet.index', ['type' => $type]) }}"
	                            class="w-full sm:w-auto justify-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-700 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 rounded-lg font-medium text-sm transition-colors inline-flex items-center gap-2"
	                        >
	                            <i data-lucide="x" class="size-4"></i>
	                            Reset
	                        </a>
	                    @endif
	                </div>
	            </form>
	        </div>

	        <!-- Saldo Tersedia Card -->
		        <div class="relative rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-indigo-500 to-purple-600 dark:from-indigo-600 dark:to-purple-700 p-6 shadow-lg">
		            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
		                <div>
		                    <p class="text-sm font-medium text-white/80 mb-1">
		                        <i data-lucide="wallet" class="size-4 inline mr-1"></i>
                            @if($isMarketing && $type === 'withdrawal')
                                Saldo Komisi Tersedia
                            @else
                                Saldo Tersedia {{ $type === 'topup' ? 'Top-up' : 'Penarikan' }}
                            @endif
	                    </p>
	                    <p class="text-3xl font-bold text-white">
                            @php
                                $displayBalance = ($isMarketing && $type === 'withdrawal')
                                    ? ($availableCommission ?? 0)
                                    : $totalBalance;
                            @endphp
                            @if($isMarketing && $type === 'withdrawal')
                                Rp <span id="marketingAvailableCommissionDisplay">{{ number_format($displayBalance, 0, ',', '.') }}</span>
                            @else
	                            Rp {{ number_format($displayBalance, 0, ',', '.') }}
                            @endif
	                    </p>
	                    <p class="text-xs text-white/70 mt-2">
	                        @if(request('start_date') && request('end_date'))
	                            Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
	                        @elseif(request('start_date'))
                            Dari: {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }}
                        @elseif(request('end_date'))
                            Sampai: {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                        @else
                            Total keseluruhan transaksi yang disetujui
                        @endif
		                    </p>
		                </div>
	                    <div class="flex items-center justify-between sm:justify-start gap-3 w-full sm:w-auto">
	                        @if($isMarketing && $type === 'withdrawal')
	                            <button
	                                type="button"
	                                onclick="openMarketingWithdrawModal()"
	                                class="w-full sm:w-auto justify-center inline-flex items-center gap-2 rounded-lg bg-white/20 hover:bg-white/30 px-4 py-2 text-sm font-semibold text-white transition-colors"
	                            >
	                                <i data-lucide="credit-card" class="size-4"></i>
	                                <span>Request Penarikan</span>
	                            </button>
	                        @endif
	                        <div class="absolute top-4 right-4 sm:static rounded-full bg-white/20 p-3 sm:p-4">
	                            <i data-lucide="trending-up" class="size-6 sm:size-8 text-white"></i>
	                        </div>
	                    </div>
		            </div>
		        </div>

	        <!-- Summary Cards -->
            @if(! $isMarketing)
	        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
	            <!-- Card 1: Pending Transactions -->
	            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-800 dark:to-neutral-900 p-6 shadow-sm">
	                <div class="flex items-start justify-between">
	                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Menunggu Persetujuan</p>
                        <p class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">{{ \App\Models\SaleTransaction::where('status', 'pending')->where('transaction_type', $type)->count() }}</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400">
                                <i data-lucide="clock" class="size-3"></i>
                                Menunggu tindakan
                            </span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-neutral-300 dark:bg-neutral-600 p-3">
                        <i data-lucide="clock" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Card 2: Approved Transactions -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/30 dark:to-emerald-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Disetujui</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-900 dark:text-emerald-100">{{ \App\Models\SaleTransaction::where('status', 'success')->where('transaction_type', $type)->count() }}</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                <i data-lucide="check-circle" class="size-3"></i>
                                Sudah diproses
                            </span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-emerald-400 dark:bg-emerald-600 p-3">
                        <i data-lucide="check-circle" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Card 3: Rejected Transactions -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/30 dark:to-amber-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-700 dark:text-amber-300">Ditolak</p>
                        <p class="mt-2 text-3xl font-bold text-amber-900 dark:text-amber-100">{{ \App\Models\SaleTransaction::where('status', 'failed')->where('transaction_type', $type)->count() }}</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400">
                                <i data-lucide="x-circle" class="size-3"></i>
                                Tidak disetujui
                            </span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-amber-400 dark:bg-amber-600 p-3">
                        <i data-lucide="x-circle" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Card 4: Total Transactions -->
	            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Total {{ $type === 'topup' ? 'Top-up' : 'Penarikan' }}</p>
                        <p class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100">{{ \App\Models\SaleTransaction::where('transaction_type', $type)->count() }}</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                <i data-lucide="{{ $type === 'topup' ? 'smartphone' : 'credit-card' }}" class="size-3"></i>
                                Semua permintaan
                            </span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-blue-400 dark:bg-blue-600 p-3">
                        <i data-lucide="{{ $type === 'topup' ? 'smartphone' : 'credit-card' }}" class="size-6 text-white"></i>
                    </div>
                </div>
	            </div>
	        </div>
            @endif

        <!-- Top-up Requests Table -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                ID Pengguna
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Nickname
                            </th>
	                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
	                                Role
	                            </th>
	                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
	                                Nominal
	                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Metode Pembayaran
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Bukti Transfer
                            </th>
	                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
	                                Status
	                            </th>
	                            @unless($isMarketing)
	                                <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
	                                    Aksi
	                                </th>
	                            @endunless
	                        </tr>
	                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($transactions as $index => $transaction)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-neutral-900 dark:text-white">{{ $transactions->firstItem() + $index }}</div>
                                </td>
	                                <td class="px-6 py-4 whitespace-nowrap">
	                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">
	                                        {{ $transaction->user_id_input
	                                            ?? $transaction->account_number
	                                            ?? $transaction->customer_name
	                                            ?? ($transaction->user?->username ?? $transaction->user?->email ?? (string) ($transaction->user_id ?? ''))
	                                            ?? 'N/A'
	                                        }}
	                                    </div>
	                                </td>
	                                <td class="px-6 py-4 whitespace-nowrap">
	                                    <div class="text-sm text-neutral-900 dark:text-white">
	                                        {{ $transaction->nickname
	                                            ?? $transaction->account_name
	                                            ?? $transaction->customer_phone
	                                            ?? ($transaction->user?->name ?? 'N/A')
	                                        }}
	                                    </div>
	                                </td>
	                                <td class="px-6 py-4">
	                                    @php
	                                        $userLabel = $transaction->user?->username
	                                            ?: ($transaction->user?->name ?: ($transaction->user?->email ?: null));
	                                        $roleValue = $transaction->user?->role;
	                                        $roleLabel = $roleValue ? ucfirst((string) $roleValue) : 'N/A';
	                                    @endphp
	                                    <div class="flex items-center gap-3">
	                                        @if($transaction->user)
	                                            <div class="flex-shrink-0">
	                                                @if($transaction->user->avatar && file_exists(public_path($transaction->user->avatar)))
	                                                    <img
	                                                        src="{{ asset($transaction->user->avatar) }}"
	                                                        alt="{{ $userLabel ?? 'User' }}"
	                                                        class="size-10 rounded-full object-cover border-2 border-white dark:border-neutral-500 shadow-sm"
	                                                        onerror="this.onerror=null; this.remove();"
	                                                    >
	                                                @else
	                                                    <div class="size-10 rounded-full bg-neutral-300 dark:bg-neutral-600 flex items-center justify-center border-2 border-white dark:border-neutral-500 shadow-sm">
	                                                        <span class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">
	                                                            {{ strtoupper(substr($userLabel ?? 'U', 0, 2)) }}
	                                                        </span>
	                                                    </div>
	                                                @endif
	                                            </div>
	                                            <div>
	                                                <div class="text-sm font-medium text-neutral-900 dark:text-white">
	                                                    {{ $userLabel ?? 'N/A' }}
	                                                </div>
	                                                <div class="text-xs text-neutral-500 dark:text-neutral-400">
	                                                    {{ $roleLabel }}
	                                                </div>
	                                            </div>
	                                        @else
	                                            <div class="flex-shrink-0 size-10 rounded-full bg-neutral-300 dark:bg-neutral-600 flex items-center justify-center border-2 border-white dark:border-neutral-500 shadow-sm">
	                                                <span class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">??</span>
	                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-neutral-900 dark:text-white">N/A</div>
                                                <div class="text-xs text-neutral-500 dark:text-neutral-400">-</div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
	                                <td class="px-6 py-4 whitespace-nowrap">
	                                    <div class="text-sm font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
	                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-900 dark:text-white">
                                        {{ $transaction->payment_method ?? 'N/A' }}
                                        @if($transaction->payment_number && $transaction->payment_number !== 'Lihat metode pembayaran')
                                            <br><span class="text-xs text-neutral-500 dark:text-neutral-400">{{ $transaction->payment_number }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($transaction->proof_image)
                                        <button onclick="showProofImage('{{ asset($transaction->proof_image) }}')"
                                                class="inline-flex items-center justify-center p-2 border border-neutral-900/20 text-neutral-900 bg-neutral-900/5 hover:bg-neutral-900/10 dark:border-white/20 dark:text-white dark:bg-white/5 dark:hover:bg-white/10 rounded-lg transition-colors"
                                                title="Lihat Bukti"
                                        >
                                            <i data-lucide="image" class="size-4"></i>
                                        </button>
                                    @else
                                        <span class="text-xs text-neutral-500 dark:text-neutral-400">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg border
                                        @if($transaction->status === 'pending')
                                            border-amber-400/50 text-amber-600 bg-amber-400/10 dark:border-amber-500/50 dark:text-amber-400 dark:bg-amber-500/10
                                        @elseif($transaction->status === 'success')
                                            border-emerald-400/50 text-emerald-600 bg-emerald-400/10 dark:border-emerald-500/50 dark:text-emerald-400 dark:bg-emerald-500/10
                                        @else
                                            border-red-400/50 text-red-600 bg-red-400/10 dark:border-red-500/50 dark:text-red-400 dark:bg-red-500/10
                                        @endif">
                                        @if($transaction->status === 'pending')
                                            Menunggu
                                        @elseif($transaction->status === 'success')
                                            Disetujui
                                        @else
                                            Ditolak
                                        @endif
	                                    </span>
	                                </td>
	                                @unless($isMarketing)
	                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
	                                        @if($transaction->status === 'pending')
	                                            <div class="flex items-center justify-end gap-2">
	                                                <form method="POST" action="{{ route($routePrefix.'wallet.approve', $transaction) }}" class="inline">
	                                                    @csrf
	                                                    <button
	                                                        type="submit"
	                                                        class="inline-flex items-center justify-center p-2 border border-emerald-400/50 text-emerald-600 bg-emerald-400/10 hover:bg-emerald-400/20 dark:border-emerald-500/50 dark:text-emerald-400 dark:bg-emerald-500/10 dark:hover:bg-emerald-500/20 rounded-lg transition-colors"
	                                                        title="Setujui"
	                                                    >
	                                                        <i data-lucide="check" class="size-4"></i>
	                                                    </button>
	                                                </form>
	                                                <form method="POST" action="{{ route($routePrefix.'wallet.reject', $transaction) }}" class="inline">
	                                                    @csrf
	                                                    <button
	                                                        type="submit"
	                                                        class="inline-flex items-center justify-center p-2 border border-red-400/50 text-red-600 bg-red-400/10 hover:bg-red-400/20 dark:border-red-500/50 dark:text-red-400 dark:bg-red-500/10 dark:hover:bg-red-500/20 rounded-lg transition-colors"
	                                                        title="Tolak"
	                                                    >
	                                                        <i data-lucide="x" class="size-4"></i>
	                                                    </button>
	                                                </form>
	                                            </div>
	                                        @else
	                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">-</span>
	                                        @endif
	                                    </td>
	                                @endunless
	                            </tr>
	                        @empty
	                            <tr>
	                                <td colspan="{{ $isMarketing ? 8 : 9 }}" class="px-6 py-12 text-center">
	                                    <div class="flex flex-col items-center justify-center">
	                                        <i data-lucide="smartphone" class="size-16 text-neutral-300 dark:text-neutral-700 mb-4"></i>
	                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">
	                                            Belum ada permintaan top-up menunggu persetujuan
                                        </p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                            Permintaan top-up yang membutuhkan persetujuan akan muncul di sini
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

        <!-- Modal for Proof Image -->
        <div id="proofImageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="relative max-w-2xl max-h-[90vh] bg-white dark:bg-neutral-800 rounded-xl shadow-xl overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-neutral-200 dark:border-neutral-700">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Bukti Transfer</h3>
                    <button onclick="closeProofImage()" class="p-2 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg transition-colors">
                        <i data-lucide="x" class="size-5 text-neutral-500 dark:text-neutral-400"></i>
                    </button>
                </div>
                <div class="p-4">
                    <img id="proofImageDisplay" src="" alt="Bukti Transfer" class="w-full h-auto max-h-[60vh] object-contain rounded-lg">
                </div>
            </div>
        </div>

        @if($isMarketing && $type === 'withdrawal')
            {{-- Marketing Withdraw Request Modal --}}
            <div id="marketingWithdrawModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
                <div class="relative w-full max-w-md max-h-[90vh] bg-white dark:bg-neutral-800 rounded-2xl shadow-xl flex flex-col">
                    <div class="sticky top-0 z-10 flex items-center justify-between p-4 border-b border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800">
                        <h3 class="text-lg font-bold text-neutral-900 dark:text-white">Request Penarikan Komisi</h3>
                        <button type="button" onclick="closeMarketingWithdrawModal()" class="p-2 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg transition-colors">
                            <i data-lucide="x" class="size-5 text-neutral-500 dark:text-neutral-400"></i>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        <div class="rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 dark:from-indigo-600 dark:to-purple-700 p-4 text-white">
                            <p class="text-xs font-medium text-white/80 mb-1">Saldo Komisi Tersedia</p>
                            <p class="text-2xl font-bold">Rp <span id="marketingWithdrawBalanceDisplay">{{ number_format($availableCommission ?? 0, 0, ',', '.') }}</span></p>
                        </div>

                        <div class="rounded-xl border border-indigo-200 bg-indigo-50 p-3 text-xs text-indigo-900 dark:border-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-100">
                            Request penarikan akan dikirim ke admin melalui Telegram. Jika ditolak, saldo komisi akan otomatis kembali.
                        </div>

                        <div class="space-y-3">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Jumlah Penarikan</label>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-neutral-600 dark:text-neutral-400">Rp 0</span>
                                    <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400" id="marketingWithdrawAmountDisplay">Rp 0</span>
                                    <span class="text-xs text-neutral-600 dark:text-neutral-400" id="marketingWithdrawMaxDisplay">Rp 0</span>
                                </div>
                                <input
                                    type="range"
                                    id="marketingWithdrawAmountSlider"
                                    min="0"
                                    max="0"
                                    value="0"
                                    step="1"
                                    class="w-full h-2 bg-neutral-200 rounded-lg appearance-none cursor-pointer dark:bg-neutral-700 accent-indigo-600"
                                    oninput="updateMarketingWithdrawAmount(this.value)"
                                />
                                <input type="hidden" id="marketingWithdrawAmount" value="0" />
                            </div>

                            <div class="grid grid-cols-4 gap-2">
                                <button type="button" onclick="setMarketingWithdrawAmount(Math.floor(marketingMaxBalance * 0.25))" class="px-3 py-2 text-xs font-medium text-neutral-700 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600 rounded-lg transition-colors">25%</button>
                                <button type="button" onclick="setMarketingWithdrawAmount(Math.floor(marketingMaxBalance * 0.5))" class="px-3 py-2 text-xs font-medium text-neutral-700 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600 rounded-lg transition-colors">50%</button>
                                <button type="button" onclick="setMarketingWithdrawAmount(Math.floor(marketingMaxBalance * 0.75))" class="px-3 py-2 text-xs font-medium text-neutral-700 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600 rounded-lg transition-colors">75%</button>
                                <button type="button" onclick="setMarketingWithdrawAmount(marketingMaxBalance)" class="px-3 py-2 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 rounded-lg transition-colors">Max</button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nama Pemilik Rekening</label>
                                <input id="marketing_account_name" type="text" class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-neutral-900 dark:focus:ring-offset-neutral-800 focus:border-transparent" placeholder="Nama pemilik rekening">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Bank / E-Wallet</label>
                                <input id="marketing_bank_name" type="text" class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-neutral-900 dark:focus:ring-offset-neutral-800 focus:border-transparent" placeholder="Contoh: BCA / DANA">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">No. Rekening</label>
                                <input id="marketing_account_number" type="text" class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-neutral-900 dark:focus:ring-offset-neutral-800 focus:border-transparent" placeholder="Nomor rekening">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Alamat (Opsional)</label>
                                <textarea id="marketing_address" rows="2" class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-neutral-900 dark:focus:ring-offset-neutral-800 focus:border-transparent" placeholder="Alamat lengkap"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-t border-neutral-200 dark:border-neutral-700">
                        <button type="button" onclick="submitMarketingWithdrawRequest()" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 px-4 py-3 text-sm font-bold text-white transition-colors">
                            <i data-lucide="send" class="size-4"></i>
                            <span>Kirim Request</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Initialize Lucide icons
        function initLucideIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Try to initialize immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLucideIcons);
        } else {
            initLucideIcons();
        }

        // Also try to initialize after a short delay to ensure all elements are rendered
        setTimeout(initLucideIcons, 100);

        // Show proof image modal
        function showProofImage(imageUrl) {
            const modal = document.getElementById('proofImageModal');
            const image = document.getElementById('proofImageDisplay');
            image.src = imageUrl;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            initLucideIcons();
        }

        // Close proof image modal
        function closeProofImage() {
            const modal = document.getElementById('proofImageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('proofImageModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeProofImage();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProofImage();
            }
        });

        @if($isMarketing && $type === 'withdrawal')
            let marketingMaxBalance = {{ (int) ($availableCommission ?? 0) }};

            function formatNumber(num) {
                const n = parseInt(num || 0, 10);
                return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function openMarketingWithdrawModal() {
                if (!marketingMaxBalance || marketingMaxBalance <= 0) {
                    toastr.error('Saldo komisi belum tersedia.');
                    return;
                }

                const modal = document.getElementById('marketingWithdrawModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                const slider = document.getElementById('marketingWithdrawAmountSlider');
                slider.max = marketingMaxBalance;

                document.getElementById('marketingWithdrawMaxDisplay').innerText = 'Rp ' + formatNumber(marketingMaxBalance);
                document.getElementById('marketingWithdrawBalanceDisplay').innerText = formatNumber(marketingMaxBalance);

                setMarketingWithdrawAmount(marketingMaxBalance);

                initLucideIcons();
            }

            function closeMarketingWithdrawModal() {
                const modal = document.getElementById('marketingWithdrawModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            function updateMarketingWithdrawAmount(value) {
                const amount = Math.min(parseInt(value || 0, 10), marketingMaxBalance);
                document.getElementById('marketingWithdrawAmount').value = amount;
                document.getElementById('marketingWithdrawAmountDisplay').innerText = 'Rp ' + formatNumber(amount);
            }

            function setMarketingWithdrawAmount(amount) {
                const slider = document.getElementById('marketingWithdrawAmountSlider');
                slider.value = amount;
                updateMarketingWithdrawAmount(amount);
            }

            document.getElementById('marketingWithdrawModal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeMarketingWithdrawModal();
                }
            });

            async function submitMarketingWithdrawRequest() {
                const name = document.getElementById('marketing_account_name').value.trim();
                const bank = document.getElementById('marketing_bank_name').value.trim();
                const number = document.getElementById('marketing_account_number').value.trim();
                const address = document.getElementById('marketing_address').value.trim();
                const amount = parseInt(document.getElementById('marketingWithdrawAmount').value || '0', 10);

                if (!name || !bank || !number) {
                    toastr.error('Mohon lengkapi data rekening terlebih dahulu.');
                    return;
                }

                if (!amount || amount <= 0) {
                    toastr.error('Jumlah penarikan harus lebih dari Rp 0.');
                    return;
                }

                if (amount > marketingMaxBalance) {
                    toastr.error('Jumlah penarikan melebihi saldo tersedia.');
                    return;
                }

                const formData = new FormData();
                formData.append('account_name', name);
                formData.append('bank_name', bank);
                formData.append('account_number', number);
                formData.append('address', address);
                formData.append('amount', amount);

                try {
                    const response = await fetch("{{ route('marketing.withdraw.request') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    });

                    let data = {};
                    try {
                        data = await response.json();
                    } catch (e) {
                        throw new Error('Response tidak valid.');
                    }

                    if (!response.ok || !data.success) {
                        const firstValidationError = data?.errors ? Object.values(data.errors).flat()?.[0] : null;
                        throw new Error(data.message || firstValidationError || 'Request gagal.');
                    }

                    marketingMaxBalance = parseInt(data.remaining_balance || 0, 10);

                    const headerBalance = document.getElementById('marketingAvailableCommissionDisplay');
                    if (headerBalance) {
                        headerBalance.innerText = formatNumber(marketingMaxBalance);
                    }

                    const modalBalance = document.getElementById('marketingWithdrawBalanceDisplay');
                    if (modalBalance) {
                        modalBalance.innerText = formatNumber(marketingMaxBalance);
                    }

                    toastr.success(data.message || 'Request penarikan berhasil diajukan.');
                    closeMarketingWithdrawModal();
                } catch (e) {
                    toastr.error(e?.message || 'Terjadi kesalahan.');
                }
            }
        @endif
    </script>
</x-layouts::app>
