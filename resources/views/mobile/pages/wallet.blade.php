<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
            Wallet
        </h2>
        @php
            // Get latest withdrawal transaction to determine help message
            $latestWithdrawal = isset($transactions) && $transactions->count() > 0 ? $transactions->first() : null;
            $helpMessage = \App\Models\Setting::getWhatsAppMessageTemplate(); // Default message
            
            if ($latestWithdrawal) {
                $status = $latestWithdrawal['status'] ?? 'pending';
                switch ($status) {
                    case 'pending':
                        $helpMessage = "Halo kak, mau nanya soal status pencairan saya yang masih menunggu persetujuan";
                        break;
                    case 'process':
                        $helpMessage = "Halo kak, mau nanya soal status pencairan saya yang sedang diproses";
                        break;
                    case 'success':
                        $helpMessage = "Halo kak, mau konfirmasi pencairan saya yang sudah disetujui";
                        break;
                    case 'failed':
                        $helpMessage = "Halo kak, mau nanya kenapa pencairan saya ditolak dan bagaimana solusinya";
                        break;
                    default:
                        $helpMessage = "Halo kak, mau nanya soal status pencairan saya";
                        break;
                }
            } else {
                $helpMessage = "Halo kak, mau nanya soal cara penarikan saldo komisi";
            }
        @endphp
        <a href="https://wa.me/{{ \App\Models\Setting::getWhatsAppNumber() }}?text={{ urlencode($helpMessage) }}"
           class="inline-flex items-center gap-2 rounded-full bg-green-500 px-3 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
            <span>Butuh bantuan?</span>
        </a>
    </div>

    {{-- Balance Card --}}
    <div class="relative overflow-hidden rounded-xl bg-neutral-700 p-6 text-white shadow-lg dark:bg-neutral-800">
        <div class="relative z-10 flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                <i data-lucide="wallet" class="size-6 text-white"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-neutral-200">Saldo Tersedia</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold tracking-tight" id="balance-display">
                        Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
                {{-- Total Komisi --}}
                <p class="mt-1 text-xs font-medium text-neutral-300">
                    Total Komisi: <span class="text-green-400 font-semibold">Rp {{ number_format($totalCommissionEarned ?? 0, 0, ',', '.') }}</span>
                </p>
                {{-- Withdrawn Commission --}}
                <p class="mt-0.5 text-xs font-medium text-neutral-300">
                    Sudah Ditarik: <span class="text-neutral-200">Rp {{ number_format($withdrawnCommission ?? 0, 0, ',', '.') }}</span>
                </p>
            </div>
        </div>

        {{-- Decorative circles --}}
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/5 blur-xl"></div>
        <div class="absolute -bottom-4 -left-4 h-24 w-24 rounded-full bg-white/5 blur-xl"></div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="rounded-xl bg-emerald-50 p-4 text-center dark:bg-emerald-900/20">
            <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Total Earned</p>
            <p class="mt-1 text-lg font-bold text-emerald-700 dark:text-emerald-300">
                Rp {{ number_format($totalCommissionEarned ?? 0, 0, ',', '.') }}
            </p>
        </div>
        <div class="rounded-xl bg-blue-50 p-4 text-center dark:bg-blue-900/20">
            <p class="text-xs font-medium text-blue-600 dark:text-blue-400">Available</p>
            <p class="mt-1 text-lg font-bold text-blue-700 dark:text-blue-300">
                Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
            </p>
        </div>
        <div class="rounded-xl bg-purple-50 p-4 text-center dark:bg-purple-900/20">
            <p class="text-xs font-medium text-purple-600 dark:text-purple-400">Withdrawn</p>
            <p class="mt-1 text-lg font-bold text-purple-700 dark:text-purple-300">
                Rp {{ number_format($withdrawnCommission ?? 0, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Date Filter --}}
    <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
        <form method="GET" action="{{ route('mobile.app', ['page' => 'wallet']) }}" class="space-y-3">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="filter" class="size-4 text-neutral-600 dark:text-neutral-400"></i>
                <h4 class="text-sm font-semibold text-neutral-900 dark:text-white">Filter Riwayat Transaksi</h4>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1.5">
                    <label for="start_date" class="text-xs font-medium text-neutral-700 dark:text-neutral-300">
                        Tanggal Mulai
                    </label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ request('start_date') }}"
                        class="w-full rounded-lg border border-neutral-300 bg-neutral-50 px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white dark:focus:border-white dark:focus:ring-white"
                    />
                </div>

                <div class="space-y-1.5">
                    <label for="end_date" class="text-xs font-medium text-neutral-700 dark:text-neutral-300">
                        Tanggal Akhir
                    </label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                        class="w-full rounded-lg border border-neutral-300 bg-neutral-50 px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white dark:focus:border-white dark:focus:ring-white"
                    />
                </div>
            </div>

            {{-- Transaction Type Filter --}}
            <div class="space-y-1.5">
                <label for="transaction_type" class="text-xs font-medium text-neutral-700 dark:text-neutral-300">
                    <i data-lucide="arrow-left-right" class="size-3 inline mr-1"></i>
                    Jenis Transaksi
                </label>
                <select
                    id="transaction_type"
                    name="transaction_type"
                    class="w-full rounded-lg border border-neutral-300 bg-neutral-50 px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white dark:focus:border-white dark:focus:ring-white"
                >
                    <option value="all" {{ request('transaction_type', 'all') === 'all' ? 'selected' : '' }}>
                        Semua Transaksi
                    </option>
                    <option value="income" {{ request('transaction_type') === 'income' ? 'selected' : '' }}>
                        Pemasukan (Komisi)
                    </option>
                    <option value="outcome" {{ request('transaction_type') === 'outcome' ? 'selected' : '' }}>
                        Pengeluaran (Penarikan)
                    </option>
                </select>
            </div>

            <div class="flex gap-2">
                <button
                    type="submit"
                    class="flex-1 rounded-lg bg-neutral-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-neutral-800 dark:bg-white dark:text-black dark:hover:bg-neutral-200 inline-flex items-center justify-center gap-2"
                >
                    <i data-lucide="search" class="size-4"></i>
                    Filter
                </button>
                
                @if(request('start_date') || request('end_date') || request('transaction_type', 'all') !== 'all')
                    <a
                        href="{{ route('mobile.app', ['page' => 'wallet']) }}"
                        class="rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600 inline-flex items-center justify-center gap-2"
                    >
                        <i data-lucide="x" class="size-4"></i>
                        Reset
                    </a>
                @endif
            </div>

            @if(request('start_date') || request('end_date') || request('transaction_type', 'all') !== 'all')
                <div class="rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                    <p class="text-xs text-blue-700 dark:text-blue-300">
                        <i data-lucide="info" class="size-3 inline mr-1"></i>
                        <strong>Filter aktif:</strong>
                        @if(request('transaction_type') === 'income')
                            Pemasukan
                        @elseif(request('transaction_type') === 'outcome')
                            Pengeluaran
                        @endif
                        @if(request('start_date') && request('end_date'))
                            {{ request('transaction_type', 'all') !== 'all' ? ' | ' : '' }}{{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                        @elseif(request('start_date'))
                            {{ request('transaction_type', 'all') !== 'all' ? ' | ' : '' }}Dari {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }}
                        @elseif(request('end_date'))
                            {{ request('transaction_type', 'all') !== 'all' ? ' | ' : '' }}Sampai {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                        @endif
                    </p>
                </div>
            @endif
        </form>
    </div>

    {{-- Transaction History Section --}}
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
            Riwayat Transaksi
        </h3>

        @if(isset($transactions) && $transactions->count() > 0)
            <div class="space-y-3">
                @foreach($transactions as $transaction)
                    <div class="rounded-xl border 
                        @if($transaction['type'] === 'income')
                            border-emerald-200 bg-emerald-50/50 dark:border-emerald-900/30 dark:bg-emerald-900/10
                        @elseif($transaction['status'] === 'pending' || $transaction['status'] === 'process')
                            border-amber-200 bg-amber-50/50 dark:border-amber-900/30 dark:bg-amber-900/10
                        @elseif($transaction['status'] === 'success')
                            border-blue-200 bg-blue-50/50 dark:border-blue-900/30 dark:bg-blue-900/10
                        @else
                            border-red-200 bg-red-50/50 dark:border-red-900/30 dark:bg-red-900/10
                        @endif p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full
                                        @if($transaction['type'] === 'income')
                                            bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400
                                        @elseif($transaction['status'] === 'pending' || $transaction['status'] === 'process')
                                            bg-amber-100 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400
                                        @elseif($transaction['status'] === 'success')
                                            bg-blue-100 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400
                                        @else
                                            bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400
                                        @endif">
                                        @if($transaction['type'] === 'income')
                                            <i data-lucide="arrow-down-left" class="size-4"></i>
                                        @elseif($transaction['status'] === 'pending' || $transaction['status'] === 'process')
                                            <i data-lucide="clock" class="size-4"></i>
                                        @elseif($transaction['status'] === 'success')
                                            <i data-lucide="arrow-up-right" class="size-4"></i>
                                        @else
                                            <i data-lucide="x-circle" class="size-4"></i>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if(isset($transaction['service_image']) && $transaction['service_image'])
                                            <img src="{{ asset($transaction['service_image']) }}" 
                                                 alt="{{ $transaction['source'] }}" 
                                                 class="size-6 rounded object-cover border border-neutral-200 dark:border-neutral-700">
                                        @endif
                                        <div>
                                            <p class="font-semibold text-sm text-neutral-900 dark:text-white">
                                                {{ $transaction['source'] }}
                                            </p>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ \Carbon\Carbon::parse($transaction['date'])->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 space-y-1.5 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-neutral-600 dark:text-neutral-400">
                                            @if($transaction['type'] === 'income')
                                                Komisi Masuk:
                                            @else
                                                Penarikan:
                                            @endif
                                        </span>
                                        <span class="font-bold
                                            @if($transaction['type'] === 'income')
                                                text-emerald-600 dark:text-emerald-400
                                            @else
                                                text-red-600 dark:text-red-400
                                            @endif">
                                            @if($transaction['type'] === 'income')
                                                +
                                            @else
                                                -
                                            @endif
                                            @if($transaction['type'] === 'income' && isset($transaction['transaction_amount']))
                                                Rp {{ number_format($transaction['transaction_amount'], 0, ',', '.') }}
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400 block">
                                                    (Komisi: Rp {{ number_format($transaction['amount'], 0, ',', '.') }})
                                                </span>
                                            @else
                                                Rp {{ number_format($transaction['amount'], 0, ',', '.') }}
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between border-t border-neutral-200 dark:border-neutral-700 pt-1.5">
                                        <span class="text-xs font-medium text-neutral-700 dark:text-neutral-300">
                                            Sisa Saldo:
                                        </span>
                                        <span class="font-bold text-neutral-900 dark:text-white">
                                            Rp {{ number_format($transaction['balance_after'], 0, ',', '.') }}
                                        </span>
                                    </div>

                                    @if($transaction['type'] === 'outcome')
                                        <div class="mt-2 space-y-1 rounded-lg bg-white/50 p-2 dark:bg-neutral-800/50">
                                            @if(isset($transaction['bank_name']))
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-neutral-600 dark:text-neutral-400">Bank:</span>
                                                    <span class="font-medium text-neutral-900 dark:text-white">{{ $transaction['bank_name'] }}</span>
                                                </div>
                                            @endif
                                            @if(isset($transaction['account_number']))
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-neutral-600 dark:text-neutral-400">No. Rek:</span>
                                                    <span class="font-medium text-neutral-900 dark:text-white">{{ $transaction['account_number'] }}</span>
                                                </div>
                                            @endif
                                            @if(isset($transaction['account_name']))
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-neutral-600 dark:text-neutral-400">Atas Nama:</span>
                                                    <span class="font-medium text-neutral-900 dark:text-white">{{ $transaction['account_name'] }}</span>
                                                </div>
                                            @endif
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="text-neutral-600 dark:text-neutral-400">Status:</span>
                                                <span class="font-semibold 
                                                    @if($transaction['status'] === 'pending')
                                                        text-amber-600 dark:text-amber-400
                                                    @elseif($transaction['status'] === 'process')
                                                        text-blue-600 dark:text-blue-400
                                                    @elseif($transaction['status'] === 'success')
                                                        text-emerald-600 dark:text-emerald-400
                                                    @else
                                                        text-red-600 dark:text-red-400
                                                    @endif">
                                                    @if($transaction['status'] === 'pending')
                                                        Menunggu Konfirmasi
                                                    @elseif($transaction['status'] === 'process')
                                                        Sedang Diproses
                                                    @elseif($transaction['status'] === 'success')
                                                        Berhasil
                                                    @else
                                                        Ditolak
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if(isset($totalPages) && $totalPages > 1)
                <div class="flex items-center justify-center gap-2 mt-6">
                    {{-- Previous Button --}}
                    @if($currentPage > 1)
                        <a href="{{ route('mobile.app', array_merge(request()->query(), ['page' => 'wallet', 'trans_page' => $currentPage - 1])) }}" 
                           class="flex items-center justify-center w-10 h-10 rounded-lg border border-neutral-300 hover:bg-neutral-100 dark:border-neutral-600 dark:hover:bg-neutral-700 transition-colors">
                            <i data-lucide="chevron-left" class="size-5 text-neutral-600 dark:text-neutral-400"></i>
                        </a>
                    @else
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg border border-neutral-300 bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-800 cursor-not-allowed opacity-50">
                            <i data-lucide="chevron-left" class="size-5 text-neutral-400 dark:text-neutral-600"></i>
                        </div>
                    @endif

                    {{-- Page Numbers --}}
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= $totalPages; $i++)
                            @if($i === (int)$currentPage)
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-600 text-white font-semibold text-sm">
                                    {{ $i }}
                                </div>
                            @else
                                <a href="{{ route('mobile.app', array_merge(request()->query(), ['page' => 'wallet', 'trans_page' => $i])) }}" 
                                   class="flex items-center justify-center w-10 h-10 rounded-lg border border-neutral-300 hover:bg-neutral-100 dark:border-neutral-600 dark:hover:bg-neutral-700 transition-colors text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    {{ $i }}
                                </a>
                            @endif
                        @endfor
                    </div>

                    {{-- Next Button --}}
                    @if($currentPage < $totalPages)
                        <a href="{{ route('mobile.app', array_merge(request()->query(), ['page' => 'wallet', 'trans_page' => $currentPage + 1])) }}" 
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
            <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-neutral-300 bg-neutral-50 py-12 dark:border-neutral-700 dark:bg-neutral-900">
                <i data-lucide="inbox" class="size-12 text-neutral-300 dark:text-neutral-700 mb-4"></i>
                <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Belum ada riwayat transaksi</p>
            </div>
        @endif
    </div>

    {{-- Withdraw Form Section --}}
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
            Form Penarikan
        </h3>
        
        <button type="button" onclick="openWithdrawModal()" class="w-full rounded-lg bg-neutral-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-neutral-800 dark:bg-white dark:text-black dark:hover:bg-neutral-200 inline-flex items-center justify-center gap-2">
            <i data-lucide="credit-card" class="size-5"></i>
            Tarik Saldo
        </button>
    </div>
</div>

{{-- Withdraw Modal --}}
<div id="withdrawModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="relative w-full max-w-md max-h-[90vh] bg-white dark:bg-neutral-800 rounded-2xl shadow-xl flex flex-col">
        {{-- Header (Sticky) --}}
        <div class="sticky top-0 z-10 flex items-center justify-between p-4 border-b border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800">
            <h3 class="text-lg font-bold text-neutral-900 dark:text-white">Tarik Saldo</h3>
            <button onclick="closeWithdrawModal()" class="p-2 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg transition-colors">
                <i data-lucide="x" class="size-5 text-neutral-500 dark:text-neutral-400"></i>
            </button>
        </div>
        
        {{-- Content (Scrollable) --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            {{-- Balance Display --}}
            <div class="rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 dark:from-indigo-600 dark:to-purple-700 p-4 text-white">
                <p class="text-xs font-medium text-white/80 mb-1">Saldo Tersedia</p>
                <p class="text-2xl font-bold">Rp {{ number_format($balance ?? 0, 0, ',', '.') }}</p>
            </div>

            {{-- Amount Slider --}}
            <div class="space-y-3">
                <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Jumlah Penarikan
                </label>
                
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-600 dark:text-neutral-400">Rp 0</span>
                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400" id="withdrawAmountDisplay">
                            Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                        </span>
                        <span class="text-xs text-neutral-600 dark:text-neutral-400" id="maxBalanceDisplay">Rp {{ number_format($balance ?? 0, 0, ',', '.') }}</span>
                    </div>
                    
                    <input 
                        type="range" 
                        id="withdrawAmountSlider" 
                        min="0" 
                        max="{{ $balance ?? 0 }}" 
                        value="{{ $balance ?? 0 }}" 
                        step="1"
                        class="w-full h-2 bg-neutral-200 rounded-lg appearance-none cursor-pointer dark:bg-neutral-700 accent-indigo-600"
                        oninput="updateWithdrawAmount(this.value)"
                    >
                    
                    <input 
                        type="hidden" 
                        id="withdrawAmount" 
                        value="{{ $balance ?? 0 }}"
                    >
                </div>
                
                {{-- Quick Amount Buttons --}}
                <div class="grid grid-cols-4 gap-2">
                    <button type="button" onclick="setWithdrawAmount(Math.floor(maxBalance * 0.25))" class="px-3 py-2 text-xs font-medium text-neutral-700 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600 rounded-lg transition-colors">
                        25%
                    </button>
                    <button type="button" onclick="setWithdrawAmount(Math.floor(maxBalance * 0.5))" class="px-3 py-2 text-xs font-medium text-neutral-700 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600 rounded-lg transition-colors">
                        50%
                    </button>
                    <button type="button" onclick="setWithdrawAmount(Math.floor(maxBalance * 0.75))" class="px-3 py-2 text-xs font-medium text-neutral-700 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600 rounded-lg transition-colors">
                        75%
                    </button>
                    <button type="button" onclick="setWithdrawAmount(maxBalance)" class="px-3 py-2 text-xs font-medium text-neutral-700 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600 rounded-lg transition-colors">
                        100%
                    </button>
                </div>
            </div>

            {{-- Bank Details --}}
            <div class="space-y-3">
                <div class="space-y-2">
                    <label for="modal_bank_name" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Bank / E-Wallet
                    </label>
                    <input type="text"
                           id="modal_bank_name"
                           class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-600 dark:focus:border-white dark:focus:ring-white"
                           placeholder="Contoh: BCA / Dana / Gopay">
                </div>

                <div class="space-y-2">
                    <label for="modal_account_number" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        No. Rekening
                    </label>
                    <input type="number"
                           id="modal_account_number"
                           class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-600 dark:focus:border-white dark:focus:ring-white"
                           placeholder="Contoh: 1234567890">
                </div>

                <div class="space-y-2">
                    <label for="modal_account_name" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Nama Pemilik Rekening
                    </label>
                    <input type="text"
                           id="modal_account_name"
                           class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-600 dark:focus:border-white dark:focus:ring-white"
                           placeholder="Masukkan nama lengkap">
                </div>

                <div class="space-y-2">
                    <label for="modal_whatsapp_number" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        No WhatsApp
                    </label>
                    <input type="text"
                           id="modal_whatsapp_number"
                           class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-600 dark:focus:border-white dark:focus:ring-white"
                           placeholder="Contoh: 081234567890">
                </div>

                <div class="space-y-2">
                    <label for="modal_address" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Alamat (Opsional)
                    </label>
                    <textarea
                           id="modal_address"
                           rows="3"
                           class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-600 dark:focus:border-white dark:focus:ring-white"
                           placeholder="Masukkan alamat lengkap (opsional)"></textarea>
                </div>
            </div>
        </div>

        {{-- Footer (Sticky) with Submit Button --}}
        <div class="sticky bottom-0 z-10 border-t border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-4">
            <button type="button" onclick="handleWithdrawFromModal()" class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 px-4 py-3 text-sm font-bold text-white transition inline-flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="size-5"></i>
                Ajukan Penarikan
            </button>
        </div>
    </div>
</div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let maxBalance = {{ $balance ?? 0 }}; // Changed from const to let

    // Modal functions
    function openWithdrawModal() {
        const modal = document.getElementById('withdrawModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Reset form inputs
        document.getElementById('modal_bank_name').value = '';
        document.getElementById('modal_account_number').value = '';
        document.getElementById('modal_account_name').value = '';
        document.getElementById('modal_whatsapp_number').value = '';
        document.getElementById('modal_address').value = '';
        
        // Update slider max value and display dynamically
        const slider = document.getElementById('withdrawAmountSlider');
        const maxBalanceDisplay = document.getElementById('maxBalanceDisplay');
        
        // Set slider properties
        slider.max = maxBalance;
        slider.value = maxBalance;
        slider.step = Math.max(1000, Math.floor(maxBalance / 100)); // Dynamic step
        
        // Update displays
        maxBalanceDisplay.innerText = 'Rp ' + formatNumber(maxBalance);
        
        // Reset slider to max
        setWithdrawAmount(maxBalance);
        
        // Re-initialize lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function closeWithdrawModal() {
        const modal = document.getElementById('withdrawModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Slider functions
    function updateWithdrawAmount(value) {
        const amount = parseInt(value);
        document.getElementById('withdrawAmount').value = amount;
        document.getElementById('withdrawAmountDisplay').innerText = 'Rp ' + formatNumber(amount);
    }

    function setWithdrawAmount(amount) {
        const slider = document.getElementById('withdrawAmountSlider');
        slider.value = amount;
        updateWithdrawAmount(amount);
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Close modal when clicking outside
    document.getElementById('withdrawModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeWithdrawModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeWithdrawModal();
        }
    });

    async function handleWithdrawFromModal() {
        // Get form values from modal
        const name = document.getElementById('modal_account_name').value;
        const bank = document.getElementById('modal_bank_name').value;
        const number = document.getElementById('modal_account_number').value;
        const whatsapp = document.getElementById('modal_whatsapp_number').value;
        const address = document.getElementById('modal_address').value;
        const amount = parseInt(document.getElementById('withdrawAmount').value);

        console.log('Withdraw amount:', amount, 'Max balance:', maxBalance);

        // Validate
        if (!name || !bank || !number) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Mohon lengkapi data rekening terlebih dahulu',
                confirmButtonColor: '#fbbf24',
                background: '#1f2937',
                color: '#fff'
            });
            return;
        }

        if (amount <= 0 || isNaN(amount)) {
            Swal.fire({
                icon: 'warning',
                title: 'Jumlah Tidak Valid',
                text: 'Jumlah penarikan harus lebih dari Rp 0',
                confirmButtonColor: '#fbbf24',
                background: '#1f2937',
                color: '#fff'
            });
            return;
        }

        if (amount > maxBalance) {
            Swal.fire({
                icon: 'warning',
                title: 'Saldo Tidak Cukup',
                text: `Jumlah penarikan (Rp ${formatNumber(amount)}) melebihi saldo tersedia (Rp ${formatNumber(maxBalance)})`,
                confirmButtonColor: '#fbbf24',
                background: '#1f2937',
                color: '#fff'
            });
            return;
        }

        // Show loading
        Swal.fire({
            icon: 'info',
            title: 'Memproses...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            showConfirmButton: false,
            background: '#1f2937',
            color: '#fff'
        });

        try {
            // Submit withdrawal request
            const formData = new FormData();
            formData.append('account_name', name);
            formData.append('bank_name', bank);
            formData.append('account_number', number);
            formData.append('whatsapp_number', whatsapp);
            formData.append('address', address);
            formData.append('amount', amount);

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch('/app/withdraw', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken.content
                }
            });

            let data = {};
            try {
                data = await response.json();
            } catch (e) {
                console.error('Failed to parse JSON response:', e);
                throw new Error('Invalid server response');
            }

            if (!response.ok) {
                const errorMsg = data.message || data.errors || 'Withdrawal failed';
                throw new Error(typeof errorMsg === 'string' ? errorMsg : JSON.stringify(errorMsg));
            }

            if (!data.success) {
                throw new Error(data.message || 'Withdrawal failed');
            }

            // Update balance display and maxBalance variable first
            const balanceDisplay = document.getElementById('balance-display');
            if (balanceDisplay && data.remaining_balance !== undefined) {
                balanceDisplay.innerText = 'Rp ' + formatNumber(data.remaining_balance);
                maxBalance = data.remaining_balance; // Update global maxBalance variable
            }
            
            // Close modal
            closeWithdrawModal();
            
            // Show success
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Penarikan berhasil diajukan. Menunggu konfirmasi admin.',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK',
                background: '#1f2937',
                color: '#fff',
                iconColor: '#10b981',
                timer: 2000,
                timerProgressBar: true,
                willClose: () => {
                    // Redirect to wallet after a short delay
                    setTimeout(() => {
                        window.location.href = '/app/wallet';
                    }, 500);
                }
            });

        } catch (error) {
            console.error('Withdraw error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                html: `<div style="text-align: left; word-break: break-word;">Error: ${error.message || 'Terjadi kesalahan saat memproses penarikan'}</div>`,
                confirmButtonColor: '#ef4444',
                background: '#1f2937',
                color: '#fff'
            });
        }
    }

    async function handleWithdraw() {
        // Get form values
        const name = document.getElementById('account_name').value;
        const bank = document.getElementById('bank_name').value;
        const number = document.getElementById('account_number').value;
        const whatsapp = document.getElementById('whatsapp_number').value;
        const address = document.getElementById('address').value;
        const balanceDisplay = document.getElementById('balance-display');
        const balance = balanceDisplay.innerText;

        // Validate
        if (!name || !bank || !number) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Mohon lengkapi data rekening terlebih dahulu',
                confirmButtonColor: '#fbbf24',
                background: '#1f2937',
                color: '#fff'
            });
            return;
        }

        // Show loading
        Swal.fire({
            icon: 'info',
            title: 'Memproses...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            showConfirmButton: false,
            background: '#1f2937',
            color: '#fff'
        });

        try {
            // Submit withdrawal request
            const formData = new FormData();
            formData.append('account_name', name);
            formData.append('bank_name', bank);
            formData.append('account_number', number);
            formData.append('whatsapp_number', whatsapp);
            formData.append('address', address);
            formData.append('amount', maxBalance);

            const response = await fetch('/app/withdraw', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Withdrawal failed');
            }

            // Update UI to show new balance
            if (data.remaining_balance !== undefined) {
                balanceDisplay.innerText = 'Rp ' + formatNumber(data.remaining_balance);
            }

            // Show success
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Penarikan berhasil diajukan. Menunggu konfirmasi admin.',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK',
                background: '#1f2937',
                color: '#fff',
                iconColor: '#10b981',
                timer: 2000,
                timerProgressBar: true,
                willClose: () => {
                    // Redirect to wallet
                    setTimeout(() => {
                        window.location.href = '/app/wallet';
                    }, 500);
                }
            });

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Terjadi kesalahan saat memproses penarikan',
                confirmButtonColor: '#ef4444',
                background: '#1f2937',
                color: '#fff'
            });
        }
    }
</script>
