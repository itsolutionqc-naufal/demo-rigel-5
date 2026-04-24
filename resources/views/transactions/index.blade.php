<x-layouts::app :title="__('Riwayat Transaksi')">
    @php
        $routePrefix = auth()->user()->isMarketing() ? 'marketing.' : '';
        $transactionsBaseUrl = auth()->user()->isMarketing()
            ? url('/marketing/transactions')
            : url('/transactions');
    @endphp
    <div class="container mx-auto px-4 py-6 space-y-6">
        <!-- Header Section -->
	        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
	            <div>
	                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
	                    Riwayat Transaksi
	                </h1>
	                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
	                    Lihat semua riwayat transaksi dalam sistem
	                </p>
	            </div>
	            @if(auth()->user()->isAdmin())
		            <button type="button"
		                    onclick="openAddModal()"
		                    class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
		                <i data-lucide="plus" class="size-4"></i>
		                <span>Tambah Transaksi</span>
		            </button>
	            @endif
	        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
	            <!-- Card 1: Total Transactions -->
	            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-800 dark:to-neutral-900 p-6 shadow-sm">
	                <div class="flex items-start justify-between">
	                    <div>
	                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Transaksi</p>
	                        <p class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">{{ $metrics['total_transactions'] ?? 0 }}</p>
	                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">All time</p>
	                    </div>
	                    <div class="rounded-lg bg-neutral-300 dark:bg-neutral-600 p-3">
	                        <i data-lucide="credit-card" class="size-6 text-white"></i>
	                    </div>
	                </div>
	            </div>

	            <!-- Card 2: Successful Transactions -->
	            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-800 dark:to-neutral-900 p-6 shadow-sm">
	                <div class="flex items-start justify-between">
	                    <div>
	                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Transaksi Sukses</p>
	                        <p class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">{{ $metrics['success_transactions'] ?? 0 }}</p>
	                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">All time</p>
	                    </div>
	                    <div class="rounded-lg bg-neutral-300 dark:bg-neutral-600 p-3">
	                        <i data-lucide="check-circle" class="size-6 text-white"></i>
	                    </div>
	                </div>
	            </div>

	            <!-- Card 3: Total Amount -->
	            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-800 dark:to-neutral-900 p-6 shadow-sm">
	                <div class="flex items-start justify-between">
	                    <div>
	                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Nilai Transaksi</p>
	                        <p class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">
	                            @php
	                                $totalAmount = (float) ($metrics['total_amount'] ?? 0);
	                                if($totalAmount >= 100000000) {
	                                    echo number_format($totalAmount / 100000000, 2, '.', '') . 'M';
	                                } elseif($totalAmount >= 1000000) {
	                                    echo number_format($totalAmount / 1000000, 2, '.', '') . 'jt';
	                                } else {
                                    echo 'Rp ' . number_format($totalAmount, 0, ',', '.');
                                }
	                            @endphp
	                        </p>
	                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">All time</p>
	                    </div>
	                    <div class="rounded-lg bg-neutral-300 dark:bg-neutral-600 p-3">
	                        <i data-lucide="receipt-text" class="size-6 text-white"></i>
	                    </div>
	                </div>
	            </div>

	            <!-- Card 4: Pending Transactions -->
	            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-800 dark:to-neutral-900 p-6 shadow-sm">
	                <div class="flex items-start justify-between">
	                    <div>
	                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Menunggu Konfirmasi</p>
	                        <p class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">{{ $metrics['process_transactions'] ?? 0 }}</p>
	                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">All time</p>
	                    </div>
	                    <div class="rounded-lg bg-neutral-300 dark:bg-neutral-600 p-3">
	                        <i data-lucide="clock" class="size-6 text-white"></i>
	                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
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
                                Avatar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Layanan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Tipe Transaksi
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Jumlah
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
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
	                        @forelse($transactions as $index => $transaction)
	                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-neutral-900 dark:text-white">{{ $transactions->firstItem() + $index }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $idFromUserId = $transaction->user_id ? (string) $transaction->user_id : null;
                                        $idPengguna = $transaction->user_id_input
                                            ?? $transaction->account_number
                                            ?? $transaction->customer_name
                                            ?? ($transaction->user?->username ?? $transaction->user?->email ?? $idFromUserId)
                                            ?? 'N/A';
                                    @endphp
                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                        {{ $idPengguna }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $nickname = $transaction->nickname
                                            ?? $transaction->account_name
                                            ?? $transaction->customer_phone
                                            ?? ($transaction->user?->name ?? null)
                                            ?? 'N/A';
                                    @endphp
                                    <div class="text-sm text-neutral-900 dark:text-white">
                                        {{ $nickname }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $userLabel = $transaction->user?->username
                                            ?: ($transaction->user?->name ?: ($transaction->user?->email ?: null));
                                        $profileName = $userLabel ?? $nickname ?? 'N/A';
                                        $profileEmail = $transaction->user?->email ?? '-';
                                        $avatarSeed = $transaction->user?->username ?? $transaction->user?->name ?? $profileName;

                                        $roleValue = $transaction->user?->role;
                                        $roleLabel = $roleValue ? ucfirst((string) $roleValue) : 'N/A';
                                    @endphp

                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            @if($transaction->user && $transaction->user->avatar && file_exists(public_path($transaction->user->avatar)))
                                                <img
                                                    src="{{ asset($transaction->user->avatar) }}"
                                                    alt="{{ $profileName }}"
                                                    class="size-10 rounded-full object-cover border-2 border-white dark:border-neutral-500 shadow-sm"
                                                    onerror="this.onerror=null; this.remove();"
                                                >
                                            @else
                                                <div class="size-10 rounded-full bg-neutral-300 dark:bg-neutral-600 flex items-center justify-center border-2 border-white dark:border-neutral-500 shadow-sm">
                                                    <span class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">
                                                        {{ $avatarSeed ? strtoupper(substr($avatarSeed, 0, 2)) : '??' }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <div class="text-sm font-medium text-neutral-900 dark:text-white truncate">
                                                {{ $profileName }}
                                            </div>
                                            <div class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                                {{ $profileEmail }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full
                                        @if($roleValue === 'admin')
                                            bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200
                                        @elseif($roleValue === 'marketing')
                                            bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-200
                                        @elseif($roleValue === 'user')
                                            bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200
                                        @else
                                            bg-neutral-100 text-neutral-800 dark:bg-neutral-900/30 dark:text-neutral-200
                                        @endif">
                                        {{ $roleLabel }}
                                    </span>
                                </td>
	                                <td class="px-6 py-4 whitespace-nowrap">
	                                    <div class="text-sm text-neutral-900 dark:text-white">
	                                        @if($transaction->service_name)
	                                            <div class="flex items-center gap-2">
	                                                <span class="font-medium">{{ $transaction->service_name }}</span>
                                            </div>
                                        @elseif($transaction->transaction_type === 'topup')
                                            @php
                                                // Extract service name from description
                                                $description = $transaction->description ?? '';
                                                if (preg_match('/Top Up (.+?) - ID:/', $description, $matches)) {
                                                    echo '<span class="text-neutral-600 dark:text-neutral-400">' . $matches[1] . '</span>';
                                                } else {
                                                    echo '<span class="text-neutral-400 dark:text-neutral-500">Top Up</span>';
                                                }
                                            @endphp
                                        @elseif($transaction->transaction_type === 'withdrawal')
                                            <span class="text-neutral-400 dark:text-neutral-500">Penarikan Dana</span>
                                        @else
                                            <span class="text-neutral-400 dark:text-neutral-500">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-neutral-900 dark:text-white">
                                        @if($transaction->transaction_type === 'topup')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                                <i data-lucide="smartphone" class="size-3 mr-1"></i>
                                                Top-up
                                            </span>
                                        @elseif($transaction->transaction_type === 'withdrawal')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200">
                                                <i data-lucide="credit-card" class="size-3 mr-1"></i>
                                                Penarikan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200">
                                                <i data-lucide="activity" class="size-3 mr-1"></i>
                                                Transaksi
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                                    @if($transaction->commission_amount > 0)
                                        <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                            Komisi: Rp {{ number_format($transaction->commission_amount, 0, ',', '.') }} ({{ $transaction->commission_rate }}%)
                                        </div>
                                    @endif
                                </td>
	                                <td class="px-6 py-4">
	                                    <div class="text-sm text-neutral-900 dark:text-white">
	                                        @php
	                                            $paymentLabel = $transaction->payment_method
	                                                ?? $transaction->bank_name
	                                                ?? (in_array($transaction->transaction_type, ['user_onboarding_bonus', 'host_submit'], true) ? 'Sistem' : null)
	                                                ?? ($transaction->transaction_type === 'topup' && $transaction->payment_number ? 'QRIS' : null)
	                                                ?? 'N/A';
	                                        @endphp
	                                        {{ $paymentLabel }}
	                                        @if($transaction->payment_number && $transaction->payment_number !== 'Lihat metode pembayaran')
	                                            <br><span class="text-xs text-neutral-500 dark:text-neutral-400">{{ $transaction->payment_number }}</span>
	                                        @endif
	                                    </div>
	                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($transaction->proof_image)
                                        <button onclick="openPreviewModal('{{ $transaction->proof_image }}', '{{ $transaction->customer_name ?? $transaction->user_id_input }}')" 
                                                class="inline-flex items-center gap-1 px-3 py-1.5 border border-neutral-900/20 text-neutral-900 bg-neutral-900/5 hover:bg-neutral-900/10 dark:border-white/20 dark:text-white dark:bg-white/5 dark:hover:bg-white/10 rounded-lg transition-colors text-xs font-medium">
                                            <i data-lucide="image" class="size-3"></i>
                                            Lihat
                                        </button>
                                    @else
                                        <span class="text-xs text-neutral-500 dark:text-neutral-400">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg border
                                        @if($transaction->status === 'pending')
                                            border-amber-400/50 text-amber-600 bg-amber-400/10 dark:border-amber-500/50 dark:text-amber-400 dark:bg-amber-500/10
                                        @elseif($transaction->status === 'process')
                                            border-blue-400/50 text-blue-600 bg-blue-400/10 dark:border-blue-500/50 dark:text-blue-400 dark:bg-blue-500/10
                                        @elseif($transaction->status === 'success')
                                            border-emerald-400/50 text-emerald-600 bg-emerald-400/10 dark:border-emerald-500/50 dark:text-emerald-400 dark:bg-emerald-500/10
                                        @else
                                            border-red-400/50 text-red-600 bg-red-400/10 dark:border-red-500/50 dark:text-red-400 dark:bg-red-500/10
                                        @endif">
                                        @if($transaction->status === 'pending')
                                            Menunggu
                                        @elseif($transaction->status === 'process')
                                            Proses
                                        @elseif($transaction->status === 'success')
                                            Sukses
                                        @else
                                            Gagal
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $transaction->created_at->format('d M Y') }}
                                    <div class="text-xs text-neutral-400 dark:text-neutral-500">
                                        {{ $transaction->created_at->format('H:i') }}
                                    </div>
                                </td>
	                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
	                                    <div class="flex justify-center space-x-2">
	                                        <a href="javascript:void(0)"
	                                           onclick="openDetailModal({{ $transaction->id }})"
	                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Lihat Detail">
	                                            <i data-lucide="eye" class="size-4"></i>
	                                        </a>
	                                        @if(auth()->user()->isAdmin())
		                                        <a href="javascript:void(0)"
		                                           onclick="openStatusModal({{ $transaction->id }}, '{{ $transaction->status }}')"
		                                           class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300" title="Ubah Status">
		                                            <i data-lucide="refresh-cw" class="size-4"></i>
		                                        </a>
	                                        @endif
	                                    </div>
	                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="credit-card" class="size-16 text-neutral-300 dark:text-neutral-700 mb-4"></i>
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                            Belum ada transaksi
                                        </p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                            Transaksi yang telah dibuat akan muncul di sini
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
    </div>

	    @if(auth()->user()->isAdmin())
	    <!-- Add Transaction Modal -->
	    <div id="addModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
	        <div class="flex min-h-screen items-center justify-center p-4">
	            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeModals()"></div>
	            <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl transition-all dark:bg-neutral-800 w-full max-w-md">
                <div class="border-b border-neutral-200 dark:border-neutral-700 p-6">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Tambah Transaksi Baru</h3>
                </div>
                <form id="addForm" method="POST" action="{{ route($routePrefix.'transactions.store') }}">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="add_user_id_input" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">ID Pengguna</label>
                            <input type="text" name="user_id_input" id="add_user_id_input"
                                   class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                        </div>
                        <div>
                            <label for="add_nickname" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nickname</label>
                            <input type="text" name="nickname" id="add_nickname"
                                   class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                        </div>
                        <div>
                            <label for="add_customer_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nama Pelanggan</label>
                            <input type="text" name="customer_name" id="add_customer_name" required
                                   class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                        </div>
                        <div>
                            <label for="add_customer_phone" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nomor Telepon</label>
                            <input type="text" name="customer_phone" id="add_customer_phone"
                                   class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                        </div>
                        <div>
                            <label for="add_service_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nama Layanan</label>
                            <input type="text" name="service_name" id="add_service_name"
                                   class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                        </div>
                        <div>
                            <label for="add_transaction_type" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Tipe Transaksi</label>
                            <select name="transaction_type" id="add_transaction_type" required
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                                <option value="">Pilih Tipe Transaksi</option>
                                <option value="topup">Top-up</option>
                                <option value="withdrawal">Penarikan</option>
                                <option value="transaction">Transaksi Biasa</option>
                            </select>
                        </div>
                        <div>
                            <label for="add_amount" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Jumlah</label>
                            <input type="number" name="amount" id="add_amount" required
                                   class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                        </div>
                        <div>
                            <label for="add_payment_method" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Metode Pembayaran</label>
                            <input type="text" name="payment_method" id="add_payment_method"
                                   class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                        </div>
                        <div>
                            <label for="add_commission_rate" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Persentase Komisi (%)</label>
                            <input type="number" name="commission_rate" id="add_commission_rate" step="0.01" min="0" max="100" value="0"
                                   class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                        </div>
                    </div>
                    <div class="border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-700/50 p-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModals()" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100 rounded-lg dark:text-neutral-300 dark:hover:bg-neutral-600">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-900 hover:bg-neutral-800 rounded-lg dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Transaction Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeModals()"></div>
            <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl transition-all dark:bg-neutral-800 w-full max-w-md">
                <div class="border-b border-neutral-200 dark:border-neutral-700 p-6">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Edit Transaksi</h3>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="edit_customer_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nama Pelanggan</label>
                            <input type="text" name="customer_name" id="edit_customer_name" required
                                   class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                        </div>
                        <div>
                            <label for="edit_customer_phone" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nomor Telepon</label>
                            <input type="text" name="customer_phone" id="edit_customer_phone"
                                   class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                        </div>
                        <div>
                            <label for="edit_amount" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Jumlah</label>
                            <input type="number" name="amount" id="edit_amount" required
                                   class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                        </div>
                        <div>
                            <label for="edit_commission_rate" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Persentase Komisi (%)</label>
                            <input type="number" name="commission_rate" id="edit_commission_rate" step="0.01" min="0" max="100" required
                                   class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                        </div>
                        <div>
                            <label for="edit_status" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Status</label>
                            <select name="status" id="edit_status" required
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                                <option value="pending">Pending</option>
                                <option value="process">Sedang Proses</option>
                                <option value="success">Sukses</option>
                                <option value="failed">Gagal</option>
                            </select>
                        </div>
                    </div>
                    <div class="border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-700/50 p-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModals()" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100 rounded-lg dark:text-neutral-300 dark:hover:bg-neutral-600">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-900 hover:bg-neutral-800 rounded-lg dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeModals()"></div>
            <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl transition-all dark:bg-neutral-800 w-full max-w-md">
                <div class="border-b border-neutral-200 dark:border-neutral-700 p-6">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Konfirmasi Hapus</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                        Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE" />
                    <div class="border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-700/50 p-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModals()" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100 rounded-lg dark:text-neutral-300 dark:hover:bg-neutral-600">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
	    <div id="statusModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
	        <div class="flex min-h-screen items-center justify-center p-4">
	            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeStatusModalAndRefresh()"></div>
	            <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl transition-all dark:bg-neutral-800 w-full max-w-md">
                <div class="border-b border-neutral-200 dark:border-neutral-700 p-6 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Ubah Status Transaksi</h3>
                    <button type="button" onclick="closeStatusModalAndRefresh()" class="text-neutral-400 hover:text-neutral-600 dark:text-neutral-500 dark:hover:text-neutral-300">
                        <i data-lucide="x" class="size-6"></i>
                    </button>
                </div>
                <form id="statusForm" method="POST" onsubmit="handleSubmitStatus(event)">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="status_select" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Status Baru</label>
                            <select name="status" id="status_select" required
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                                <option value="pending">Pending</option>
                                <option value="process">Sedang Proses</option>
                                <option value="success">Sukses</option>
                                <option value="failed">Gagal</option>
                            </select>
                        </div>
                    </div>
                    <div class="border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-700/50 p-6 flex justify-end gap-3">
                        <button type="button" onclick="closeStatusModalAndRefresh()" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100 rounded-lg dark:text-neutral-300 dark:hover:bg-neutral-600">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
	        </div>
	    </div>
	    @endif

	    <!-- Transaction Detail Modal -->
	    <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
	        <div class="flex min-h-screen items-center justify-center p-4">
	            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeModals()"></div>
            <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl transition-all dark:bg-neutral-800 w-full max-w-2xl">
                <div class="border-b border-neutral-200 dark:border-neutral-700 p-6 flex justify-between items-center">
                    <h3 id="detailModalTitle" class="text-lg font-semibold text-neutral-900 dark:text-white">Detail Transaksi</h3>
                    <button type="button" onclick="closeModals()" class="text-neutral-400 hover:text-neutral-600 dark:text-neutral-500 dark:hover:text-neutral-300">
                        <i data-lucide="x" class="size-6"></i>
                    </button>
                </div>
	                <div class="p-6">
	                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
	                        <div class="space-y-4">
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">User</p>
	                                <div id="detailUserInfo" class="flex items-center gap-3 mt-1"></div>
	                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">ID Pengguna</p>
                                <p id="detailUserIdInput" class="font-semibold text-neutral-900 dark:text-white"></p>
                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Nickname</p>
                                <p id="detailNickname" class="font-semibold text-neutral-900 dark:text-white"></p>
                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Nama Pelanggan</p>
                                <p id="detailCustomerName" class="font-semibold text-neutral-900 dark:text-white"></p>
                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Nomor Telepon</p>
                                <p id="detailCustomerPhone" class="font-semibold text-neutral-900 dark:text-white"></p>
                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Layanan</p>
                                <p id="detailServiceName" class="font-semibold text-neutral-900 dark:text-white"></p>
                            </div>
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Tipe Transaksi</p>
	                                <p id="detailTransactionType" class="font-semibold text-neutral-900 dark:text-white"></p>
	                            </div>
	                        </div>

	                        <div class="space-y-4">
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Kode Transaksi</p>
	                                <p id="detailTransactionCode" class="font-semibold text-neutral-900 dark:text-white"></p>
	                            </div>
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Jumlah</p>
	                                <p id="detailAmount" class="font-semibold text-neutral-900 dark:text-white"></p>
	                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Persentase Komisi</p>
                                <p id="detailCommissionRate" class="font-semibold text-neutral-900 dark:text-white"></p>
                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Jumlah Komisi</p>
                                <p id="detailCommissionAmount" class="font-semibold text-neutral-900 dark:text-white"></p>
                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Metode Pembayaran</p>
                                <p id="detailPaymentMethod" class="font-semibold text-neutral-900 dark:text-white"></p>
                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Status</p>
                                <span id="detailStatus" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"></span>
                            </div>
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Dibuat</p>
	                                <p id="detailCreatedAt" class="font-semibold text-neutral-900 dark:text-white"></p>
	                            </div>
	                        </div>
	                    </div>

	                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
	                        <div id="withdrawalInfoSection" class="space-y-4 hidden">
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Bank</p>
	                                <p id="detailBankName" class="font-semibold text-neutral-900 dark:text-white"></p>
	                            </div>
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Nomor Rekening</p>
	                                <p id="detailAccountNumber" class="font-semibold text-neutral-900 dark:text-white"></p>
	                            </div>
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Nama Rekening</p>
	                                <p id="detailAccountName" class="font-semibold text-neutral-900 dark:text-white"></p>
	                            </div>
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">WhatsApp</p>
	                                <p id="detailWhatsappNumber" class="font-semibold text-neutral-900 dark:text-white"></p>
	                            </div>
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Alamat</p>
	                                <p id="detailAddress" class="font-semibold text-neutral-900 dark:text-white whitespace-pre-line break-words"></p>
	                            </div>
	                        </div>

	                        <div class="space-y-4">
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Deskripsi</p>
	                                <p id="detailDescription" class="font-semibold text-neutral-900 dark:text-white whitespace-pre-line break-words"></p>
	                            </div>
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Admin</p>
	                                <p id="detailAdminInfo" class="font-semibold text-neutral-900 dark:text-white"></p>
	                            </div>
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Dikonfirmasi</p>
	                                <p id="detailConfirmedAt" class="font-semibold text-neutral-900 dark:text-white"></p>
	                            </div>
	                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
	                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Selesai</p>
	                                <p id="detailCompletedAt" class="font-semibold text-neutral-900 dark:text-white"></p>
	                            </div>
	                        </div>
	                    </div>

	                    <!-- Proof of Transfer Section -->
	                    <div id="proofSection" class="mt-6 p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700 hidden">
	                        <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-3">Bukti Transfer</p>
	                        <div class="flex justify-center">
                            <img id="detailProofImage" src="" alt="Bukti Transfer" class="max-w-full max-h-64 object-contain rounded-lg border border-neutral-200 dark:border-neutral-600">
                        </div>
                    </div>
                </div>
                <div class="border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-700/50 p-6 flex justify-end">
                    <button type="button" onclick="closeModals()" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100 rounded-lg dark:text-neutral-300 dark:hover:bg-neutral-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Proof of Transfer Preview Modal -->
    <div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeModals()"></div>
            <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl transition-all dark:bg-neutral-800 w-full max-w-2xl">
                <div class="border-b border-neutral-200 dark:border-neutral-700 p-6 flex justify-between items-center">
                    <h3 id="previewModalTitle" class="text-lg font-semibold text-neutral-900 dark:text-white">Pratinjau Bukti Transfer</h3>
                    <button type="button" onclick="closeModals()" class="text-neutral-400 hover:text-neutral-600 dark:text-neutral-500 dark:hover:text-neutral-300">
                        <i data-lucide="x" class="size-6"></i>
                    </button>
                </div>
                <div class="p-6 flex items-center justify-center">
                    <div id="previewContent" class="w-full flex flex-col items-center">
                        <img id="previewImage" src="" alt="Bukti Transfer" class="max-w-full max-h-[70vh] object-contain rounded-lg border border-neutral-200 dark:border-neutral-700">
                        <p id="noImageText" class="text-neutral-500 dark:text-neutral-400 hidden mt-4">Tidak ada gambar yang tersedia</p>
                    </div>
                </div>
                <div class="border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-700/50 p-6 flex justify-end">
                    <button type="button" onclick="closeModals()" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100 rounded-lg dark:text-neutral-300 dark:hover:bg-neutral-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const transactionsBaseUrl = @json($transactionsBaseUrl);

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

	        function showModal(id) {
	            const el = document.getElementById(id);
	            if (el) el.classList.remove('hidden');
	        }

	        function hideModal(id) {
	            const el = document.getElementById(id);
	            if (el) el.classList.add('hidden');
	        }

	        function closeModals() {
	            ['addModal', 'editModal', 'deleteModal', 'statusModal', 'previewModal', 'detailModal'].forEach(hideModal);
	        }

	        @if(auth()->user()->isAdmin())
		        // Admin-only modal functions
		        function openAddModal() {
		            showModal('addModal');
		        }

		        function openEditModal(transactionId, customerName, customerPhone, amount, commissionRate, status) {
		            document.getElementById('edit_customer_name').value = customerName;
		            document.getElementById('edit_customer_phone').value = customerPhone || '';
		            document.getElementById('edit_amount').value = amount;
		            document.getElementById('edit_commission_rate').value = commissionRate;
		            document.getElementById('edit_status').value = status;

		            document.getElementById('editForm').action = transactionsBaseUrl + '/' + transactionId;
		            showModal('editModal');
		        }

		        function openDeleteModal(transactionId) {
		            document.getElementById('deleteForm').action = transactionsBaseUrl + '/' + transactionId;
		            showModal('deleteModal');
		        }

		        function openStatusModal(transactionId, status) {
		            document.getElementById('status_select').value = status;
		            document.getElementById('statusForm').action = transactionsBaseUrl + '/' + transactionId + '/status';
		            showModal('statusModal');
		        }

		        function closeStatusModalAndRefresh() {
		            hideModal('statusModal');
		            location.reload();
		        }

		        function handleSubmitStatus(event) {
		            event.preventDefault();

		            const form = document.getElementById('statusForm');
		            const formData = new FormData(form);
		            const action = form.getAttribute('action');

		            fetch(action, {
		                method: 'POST',
		                body: formData,
		                headers: {
		                    'X-Requested-With': 'XMLHttpRequest',
		                    'X-HTTP-Method-Override': 'PUT'
		                }
		            })
		                .then(async (response) => {
                            const contentType = response.headers.get('content-type') || '';
                            const isJson = contentType.includes('application/json');
                            const payload = isJson ? await response.json().catch(() => null) : null;

		                    if (!response.ok) {
                                const message = (payload && payload.message) ? payload.message : 'Terjadi kesalahan saat memperbarui status.';
		                        throw new Error(message);
		                    }

		                    return payload;
		                })
		                .then(data => {
                            if (!data) {
                                alert('Status berhasil diperbarui.');
                                closeModals();
                                location.reload();
                                return;
                            }

		                    if (data.message) {
		                        alert(data.message);
		                        closeModals();
		                        location.reload();
		                        return;
		                    }
		                })
		                .catch(error => {
		                    console.error('Error:', error);
		                    alert('Terjadi kesalahan saat memperbarui status: ' + error.message);
		                });
		        }
	        @endif

	        // Detail modal functions
		        function openDetailModal(transactionId) {
		            // Fetch transaction details via AJAX
		            fetch(`${transactionsBaseUrl}/${transactionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const transaction = data.transaction;
                        const formatDateTime = (value) => {
                            if (!value) return '-';
                            const d = new Date(value);
                            if (Number.isNaN(d.getTime())) return '-';
                            return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                        };
                        const toPublicUrl = (path) => {
                            if (!path) return null;
                            if (path.startsWith('http://') || path.startsWith('https://')) return path;
                            if (path.startsWith('/')) return path;
                            return '/' + path;
                        };

		                        // User info with avatar
		                        const userInfoContainer = document.getElementById('detailUserInfo');
		                        const userLabel = transaction.user
		                            ? (transaction.user.username || transaction.user.name)
		                            : (transaction.account_name || transaction.customer_name || transaction.user_id_input || transaction.nickname || null);
		                        if (transaction.user && userLabel) {
		                            let avatarHtml = '';
		                            if (transaction.user.avatar) {
		                                avatarHtml = `<img src="${toPublicUrl(transaction.user.avatar)}" alt="${userLabel}" class="size-10 rounded-full object-cover ring-2 ring-white dark:ring-neutral-800">`;
		                            } else {
		                                const initials = (userLabel || 'U').substring(0, 2).toUpperCase();
		                                avatarHtml = `<div class="size-10 rounded-full bg-neutral-300 dark:bg-neutral-600 flex items-center justify-center ring-2 ring-white dark:ring-neutral-800"><span class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">${initials}</span></div>`;
		                            }
	                            userInfoContainer.innerHTML = `
	                                ${avatarHtml}
	                                <div>
	                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">${userLabel || '-'}</div>
	                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">${transaction.user.email || '-'}</div>
	                                </div>
	                            `;
		                        } else {
		                            const fallbackLabel = userLabel || 'N/A';
	                            userInfoContainer.innerHTML = `
	                                <div class="size-10 rounded-full bg-neutral-300 dark:bg-neutral-600 flex items-center justify-center ring-2 ring-white dark:ring-neutral-800">
	                                    <span class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">??</span>
	                                </div>
	                                <div>
	                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">${fallbackLabel}</div>
	                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">-</div>
	                                </div>
	                            `;
	                        }

	                        // Fill in the modal with transaction details
	                        document.getElementById('detailUserIdInput').textContent = transaction.user_id_input || '-';
	                        document.getElementById('detailNickname').textContent = transaction.nickname || '-';
	                        document.getElementById('detailCustomerName').textContent = transaction.customer_name || transaction.account_name || '-';
	                        document.getElementById('detailCustomerPhone').textContent = transaction.customer_phone || transaction.whatsapp_number || '-';
	                        document.getElementById('detailTransactionCode').textContent = transaction.transaction_code || '-';
	                        document.getElementById('detailDescription').textContent = transaction.description || '-';
	                        document.getElementById('detailAdminInfo').textContent = transaction.admin
	                            ? (transaction.admin.username || transaction.admin.name || transaction.admin.email || '-')
	                            : '-';
	                        document.getElementById('detailConfirmedAt').textContent = formatDateTime(transaction.confirmed_at);
	                        document.getElementById('detailCompletedAt').textContent = formatDateTime(transaction.completed_at);
	                        
	                        // Service / label
	                        let serviceName = transaction.service_name || '';
	                        if (!serviceName && transaction.transaction_type === 'topup' && transaction.description) {
	                            const match = transaction.description.match(/Top Up (.+?) - ID:/);
	                            serviceName = match ? match[1] : 'Top Up';
	                        } else if (transaction.transaction_type === 'withdrawal') {
	                            serviceName = transaction.bank_name ? `Penarikan (${transaction.bank_name})` : 'Penarikan';
	                        }
	                        document.getElementById('detailServiceName').textContent = serviceName || '-';
	                        
	                        // Transaction type
	                        let transactionType = 'Transaksi';
	                        if (transaction.transaction_type === 'topup') transactionType = 'Top-up';
	                        else if (transaction.transaction_type === 'withdrawal') transactionType = 'Penarikan';
	                        else if (transaction.transaction_type === 'host_submit') transactionType = 'Host Submit';
	                        document.getElementById('detailTransactionType').textContent = transactionType;

                        // Format amount
                        document.getElementById('detailAmount').textContent = 'Rp ' + transaction.amount.toLocaleString('id-ID');

                        document.getElementById('detailCommissionRate').textContent = (transaction.commission_rate || 0) + '%';

                        // Format commission amount
                        document.getElementById('detailCommissionAmount').textContent = 'Rp ' + (transaction.commission_amount || 0).toLocaleString('id-ID');

	                        // Payment method
	                        let paymentInfo = transaction.payment_method
	                            || transaction.bank_name
	                            || (['user_onboarding_bonus', 'host_submit'].includes(transaction.transaction_type) ? 'Sistem' : null)
	                            || ((transaction.transaction_type === 'topup' && transaction.payment_number) ? 'QRIS' : null)
	                            || 'N/A';
	                        if (transaction.payment_number && transaction.payment_number !== 'Lihat metode pembayaran') {
	                            paymentInfo += ' - ' + transaction.payment_number;
	                        }
	                        document.getElementById('detailPaymentMethod').textContent = paymentInfo;

                        // Set status with proper styling
                        const statusElement = document.getElementById('detailStatus');
                        statusElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium';
                        if(transaction.status === 'pending') {
                            statusElement.classList.add('bg-amber-100', 'text-amber-800', 'dark:bg-amber-900/30', 'dark:text-amber-200');
                            statusElement.textContent = 'Menunggu';
                        } else if(transaction.status === 'process') {
                            statusElement.classList.add('bg-blue-100', 'text-blue-800', 'dark:bg-blue-900/30', 'dark:text-blue-200');
                            statusElement.textContent = 'Proses';
                        } else if(transaction.status === 'success') {
                            statusElement.classList.add('bg-emerald-100', 'text-emerald-800', 'dark:bg-emerald-900/30', 'dark:text-emerald-200');
                            statusElement.textContent = 'Sukses';
                        } else {
                            statusElement.classList.add('bg-red-100', 'text-red-800', 'dark:bg-red-900/30', 'dark:text-red-200');
                            statusElement.textContent = 'Gagal';
                        }

	                        document.getElementById('detailCreatedAt').textContent = formatDateTime(transaction.created_at);

	                        // Withdrawal-specific fields
	                        const withdrawalInfoSection = document.getElementById('withdrawalInfoSection');
	                        const hasWithdrawalInfo = Boolean(
	                            transaction.bank_name ||
	                            transaction.account_number ||
	                            transaction.account_name ||
	                            transaction.whatsapp_number ||
	                            transaction.address
	                        );
	                        if (hasWithdrawalInfo) {
	                            document.getElementById('detailBankName').textContent = transaction.bank_name || '-';
	                            document.getElementById('detailAccountNumber').textContent = transaction.account_number || '-';
	                            document.getElementById('detailAccountName').textContent = transaction.account_name || '-';
	                            document.getElementById('detailWhatsappNumber').textContent = transaction.whatsapp_number || '-';
	                            document.getElementById('detailAddress').textContent = transaction.address || '-';
	                            withdrawalInfoSection.classList.remove('hidden');
	                        } else {
	                            withdrawalInfoSection.classList.add('hidden');
	                        }

	                        // Handle proof of transfer image
	                        const proofSection = document.getElementById('proofSection');
	                        const proofImage = document.getElementById('detailProofImage');
	                        if(transaction.proof_image) {
	                            proofImage.src = toPublicUrl(transaction.proof_image);
	                            proofSection.classList.remove('hidden');
	                        } else {
	                            proofSection.classList.add('hidden');
	                        }

                        // Show the modal
                        document.getElementById('detailModal').classList.remove('hidden');
                        
                        // Re-initialize Lucide icons
                        if (window.lucide) {
                            lucide.createIcons();
                        }
                    } else {
                        alert('Gagal memuat detail transaksi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat detail transaksi');
                });
        }

        // Preview modal functions
	        function openPreviewModal(imagePath, customerName) {
	            if (imagePath) {
                // Files are stored in public/uploads/images/job-user/
                // Path in DB is: uploads/images/job-user/filename.ext
                // URL should be: /uploads/images/job-user/filename.ext
                let imageUrl = '/' + imagePath;

                document.getElementById('previewImage').src = imageUrl;
                document.getElementById('previewImage').classList.remove('hidden');
                document.getElementById('noImageText').classList.add('hidden');
            } else {
                document.getElementById('previewImage').classList.add('hidden');
                document.getElementById('noImageText').classList.remove('hidden');
            }

	            document.getElementById('previewModalTitle').textContent = 'Pratinjau Bukti Transfer - ' + customerName;
	            document.getElementById('previewModal').classList.remove('hidden');
	        }
	    </script>
</x-layouts::app>
