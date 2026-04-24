<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Left: Welcome Message -->
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Selamat Datang, {{ ucfirst(auth()->user()->name) }}!
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Berikut ringkasan aktivitas penjualan Anda hari ini
                </p>
            </div>

            <!-- Right: Date Filter -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                <flux:input
                    type="date"
                    name="start_date"
                    value="{{ request('start_date', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}"
                    class="w-auto"
                />
                <span class="text-neutral-400 dark:text-neutral-600">—</span>
                <flux:input
                    type="date"
                    name="end_date"
                    value="{{ request('end_date', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                    class="w-auto"
                />
                <flux:button variant="primary" size="sm" type="submit">
                    Terapkan
                </flux:button>
	            </form>
	        </div>

	        <!-- Summary Cards -->
	        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
	            <!-- Card 1: Total Penjualan -->
	            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
	                <div class="flex items-start justify-between gap-3">
	                    <div class="flex-1 min-w-0">
	                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Penjualan</p>
                        <p class="mt-2 font-bold text-neutral-900 dark:text-white truncate"
                           title="Rp {{ number_format($totalSales, 0, ',', '.') }}"
                           style="font-size: {{ $totalSales >= 1000000000 ? '1rem' : ($totalSales >= 100000000 ? '1.125rem' : ($totalSales >= 10000000 ? '1.25rem' : ($totalSales >= 1000000 ? '1.375rem' : '1.5rem'))) }}">
                            Rp {{ number_format($totalSales, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            @if(request('start_date') && request('end_date'))
                                Berdasarkan periode yang dipilih
                            @else
                                Bulan ini
                            @endif
                        </p>
                    </div>
                    <div class="flex-shrink-0 rounded-lg bg-neutral-100 dark:bg-neutral-700 p-3">
                        <i data-lucide="shopping-cart" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Komisi -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Komisi</p>
                        <p class="mt-2 font-bold text-neutral-900 dark:text-white truncate"
                           title="Rp {{ number_format($totalCommissions, 0, ',', '.') }}"
                           style="font-size: {{ $totalCommissions >= 1000000000 ? '1rem' : ($totalCommissions >= 100000000 ? '1.125rem' : ($totalCommissions >= 10000000 ? '1.25rem' : ($totalCommissions >= 1000000 ? '1.375rem' : '1.5rem'))) }}">
                            Rp {{ number_format($totalCommissions, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            @if(auth()->user()->isAdmin())
                                Komisi seluruh tim
                            @else
                                Komisi Anda
                            @endif
                        </p>
                    </div>
                    <div class="flex-shrink-0 rounded-lg bg-neutral-100 dark:bg-neutral-700 p-3">
                        <i data-lucide="dollar-sign" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Card 3: Saldo -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Saldo</p>
                        <p class="mt-2 text-2xl lg:text-3xl font-bold text-neutral-900 dark:text-white truncate" title="Rp {{ number_format($userBalance, 0, ',', '.') }}">
                            @if($userBalance >= 1000000000)
                                Rp {{ number_format($userBalance / 1000000000, 1) }} M
                            @elseif($userBalance >= 1000000)
                                Rp {{ number_format($userBalance / 1000000, 1) }} Jt
                            @elseif($userBalance >= 1000)
                                Rp {{ number_format($userBalance / 1000, 1) }} Rb
                            @else
                                Rp {{ number_format($userBalance, 0) }}
                            @endif
                        </p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            @if(auth()->user()->isAdmin())
                                Saldo seluruh tim
                            @else
                                Saldo Anda
                            @endif
                        </p>
                    </div>
                    <div class="flex-shrink-0 rounded-lg bg-neutral-100 dark:bg-neutral-700 p-3">
                        <i data-lucide="wallet" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Card 4: Transaksi Sukses -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Transaksi Sukses</p>
                        <p class="mt-2 text-2xl lg:text-3xl font-bold text-neutral-900 dark:text-white truncate" title="{{ $successfulTransactions }}">{{ $successfulTransactions }}</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            @if(auth()->user()->isAdmin())
                                Transaksi tim
                            @else
                                Transaksi Anda
                            @endif
                        </p>
                    </div>
                    <div class="flex-shrink-0 rounded-lg bg-neutral-100 dark:bg-neutral-700 p-3">
                        <i data-lucide="check-circle" class="size-6 text-white"></i>
                    </div>
	                </div>
	            </div>
	        </div>

	        <!-- Recent Articles Section -->
	        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
	            <div class="flex items-center justify-between mb-6">
	                <div>
	                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Artikel Terbaru</h2>
	                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Artikel terbaru yang ditambahkan ke sistem</p>
	                </div>
                <a href="{{ route('articles') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                    Lihat Semua →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
	                    <thead>
	                        <tr class="border-b border-neutral-200 dark:border-neutral-700">
	                            <th class="text-center py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400 w-20">Gambar</th>
	                            <th class="text-left py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400">Judul</th>
	                            <th class="text-left py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400">Kategori</th>
	                            <th class="text-left py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400">Status</th>
	                            <th class="text-left py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400">Views</th>
	                            <th class="text-left py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400">Penulis</th>
	                            <th class="text-left py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400">Tanggal</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        @forelse($recentArticles as $article)
	                            <tr class="border-b border-neutral-100 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
	                                <td class="py-3 px-4 text-center">
	                                    <a href="{{ route('articles.show', $article) }}" class="inline-flex items-center justify-center">
	                                        @if(!empty($article->image))
	                                            <img
	                                                src="{{ asset($article->image) }}"
	                                                alt="{{ $article->title }}"
	                                                loading="lazy"
	                                                class="w-12 h-12 rounded-lg object-cover border border-neutral-200 dark:border-neutral-700 shadow-sm"
	                                                onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2248%22 height=%2248%22 viewBox=%220 0 48 48%22><rect width=%2248%22 height=%2248%22 rx=%2210%22 fill=%22%23e5e7eb%22/><path d=%22M16 30l6-6 4 4 6-6 4 4v6H16z%22 fill=%22%239ca3af%22/><circle cx=%2220%22 cy=%2220%22 r=%223%22 fill=%22%239ca3af%22/></svg>';"
	                                            >
	                                        @else
	                                            <div class="w-12 h-12 rounded-lg bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center shrink-0 border border-neutral-200 dark:border-neutral-700 shadow-sm">
	                                                <i data-lucide="image" class="size-5 text-neutral-400 dark:text-neutral-500"></i>
	                                            </div>
	                                        @endif
	                                    </a>
	                                </td>
	                                <td class="py-3 px-4">
	                                    <a href="{{ route('articles.show', $article) }}" class="text-sm font-medium text-neutral-900 dark:text-white line-clamp-1 hover:underline">
	                                        {{ $article->title }}
	                                    </a>
	                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 line-clamp-1">{{ Str::limit(strip_tags($article->excerpt ?? $article->content), 50) }}</p>
	                                </td>
	                                <td class="py-3 px-4">
	                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-neutral-100 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-300">
	                                        {{ $article->category ?? '-' }}
	                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @if($article->status === 'published')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                                            <i data-lucide="check-circle" class="size-3"></i>
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400">
                                            <i data-lucide="edit-3" class="size-3"></i>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                        <i data-lucide="eye" class="size-3 inline mr-1"></i>
                                        {{ number_format($article->views ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                        {{ $article->user ? $article->user->name : '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                        {{ $article->created_at->format('d M Y') }}
                                    </span>
                                </td>
                            </tr>
	                        @empty
	                            <tr>
	                                <td colspan="7" class="py-8 text-center text-neutral-500 dark:text-neutral-400">
	                                    <i data-lucide="inbox" class="size-8 mx-auto mb-2 opacity-50"></i>
	                                    <p>Belum ada artikel</p>
	                                </td>
	                            </tr>
	                        @endforelse
                    </tbody>
	                </table>
	            </div>
	        </div>

	        <!-- Recent Transactions Section -->
	        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
	            <div class="flex items-center justify-between mb-6">
	                <div>
	                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Transaksi Terbaru</h2>
	                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Transaksi terbaru dalam sistem</p>
	                </div>
                <a href="{{ route('transactions.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                    Lihat Semua →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-neutral-200 dark:border-neutral-700">
	                            <th class="text-left py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400">Username</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400">Jumlah</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400">Komisi</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400">Status</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400">Tanggal</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-neutral-600 dark:text-neutral-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
	                        @forelse($recentTransactions as $transaction)
	                            <tr class="border-b border-neutral-100 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
	                                <td class="py-3 px-4">
	                                    @php
	                                        $customerLabel = $transaction->user?->username
	                                            ?: ($transaction->user?->name ?: ($transaction->customer_name ?: '-'));
	                                        $customerSubLabel = $transaction->user?->email
	                                            ?: ($transaction->customer_phone ?: '-');
	                                    @endphp
	                                    <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                @if($transaction->user && $transaction->user->avatar && file_exists(public_path($transaction->user->avatar)))
                                                    <img
                                                        src="{{ asset($transaction->user->avatar) }}"
                                                        alt="{{ $customerLabel }}"
                                                        class="size-10 rounded-full object-cover border-2 border-white dark:border-neutral-500 shadow-sm"
                                                        onerror="this.onerror=null; this.remove();"
                                                    >
                                                @else
                                                    <div class="size-10 rounded-full bg-neutral-300 dark:bg-neutral-600 flex items-center justify-center border-2 border-white dark:border-neutral-500 shadow-sm">
                                                        <span class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">
                                                            {{ $customerLabel && $customerLabel !== '-' ? strtoupper(substr($customerLabel, 0, 2)) : '??' }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-sm font-medium text-neutral-900 dark:text-white truncate">{{ $customerLabel }}</div>
                                                <div class="text-xs text-neutral-500 dark:text-neutral-400 truncate">{{ $customerSubLabel }}</div>
                                            </div>
	                                    </div>
	                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                        @php
                                            $amount = $transaction->amount;
                                            if($amount >= 100000000) {
                                                echo number_format($amount / 100000000, 2, '.', '') . 'M';
                                            } elseif($amount >= 1000000) {
                                                echo number_format($amount / 1000000, 2, '.', '') . 'jt';
                                            } else {
                                                echo 'Rp ' . number_format($amount, 0, ',', '.');
                                            }
                                        @endphp
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                        @php
                                            $commAmount = $transaction->commission_amount;
                                            if($commAmount >= 100000000) {
                                                echo number_format($commAmount / 100000000, 2, '.', '') . 'M';
                                            } elseif($commAmount >= 1000000) {
                                                echo number_format($commAmount / 1000000, 2, '.', '') . 'jt';
                                            } else {
                                                echo 'Rp ' . number_format($commAmount, 0, ',', '.');
                                            }
                                        @endphp
                                    </div>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                        ({{ $transaction->commission_rate }}%)
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium border
                                        @if($transaction->status === 'process')
                                            border-amber-500/50 text-amber-600 dark:text-amber-400 bg-amber-500/10 dark:bg-amber-500/20
                                        @elseif($transaction->status === 'success')
                                            border-emerald-500/50 text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 dark:bg-emerald-500/20
                                        @else
                                            border-red-500/50 text-red-600 dark:text-red-400 bg-red-500/10 dark:bg-red-500/20
                                        @endif">
                                        @if($transaction->status === 'process')
                                            Sedang Proses
                                        @elseif($transaction->status === 'success')
                                            Sukses
                                        @else
                                            Gagal
                                        @endif
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                        {{ $transaction->created_at->format('d M Y') }}
                                    </span>
                                </td>
	                                <td class="py-3 px-4">
	                                    <div class="flex items-center space-x-2">
	                                        <a href="javascript:void(0)" onclick="openTransactionDetailModal({{ $transaction->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Lihat Detail">
	                                            <i data-lucide="file-text" class="size-4"></i>
	                                        </a>
	                                        <a href="javascript:void(0)" onclick="openStatusUpdateModal({{ $transaction->id }}, @js($customerLabel), @js($transaction->status))" class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300" title="Ubah Status">
	                                            <i data-lucide="refresh-cw" class="size-4"></i>
	                                        </a>
	                                    </div>
	                                </td>
	                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-neutral-500 dark:text-neutral-400">
                                    <i data-lucide="credit-card" class="size-8 mx-auto mb-2 opacity-50"></i>
                                    <p>Belum ada transaksi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
	                </table>
	            </div>
	        </div>
	    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Transaction Detail Modal Functions
        function openTransactionDetailModal(transactionId) {
            // Fetch transaction details via AJAX
            fetch(`/transactions/${transactionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const transaction = data.transaction;

                        // Fill in the modal with transaction details
                        document.getElementById('detailCustomerName').textContent = transaction.customer_name || '-';
                        document.getElementById('detailCustomerPhone').textContent = transaction.customer_phone || '-';

                        // Format amount
                        let amount = transaction.amount;
                        let formattedAmount;
                        if(amount >= 100000000) {
                            formattedAmount = (amount / 100000000).toFixed(2) + 'M';
                        } else if(amount >= 1000000) {
                            formattedAmount = (amount / 1000000).toFixed(2) + 'jt';
                        } else {
                            formattedAmount = 'Rp ' + amount.toLocaleString('id-ID');
                        }
                        document.getElementById('detailAmount').textContent = formattedAmount;

                        document.getElementById('detailCommissionRate').textContent = transaction.commission_rate + '%';

                        // Format commission amount
                        let commAmount = transaction.commission_amount;
                        let formattedCommAmount;
                        if(commAmount >= 100000000) {
                            formattedCommAmount = (commAmount / 100000000).toFixed(2) + 'M';
                        } else if(commAmount >= 1000000) {
                            formattedCommAmount = (commAmount / 1000000).toFixed(2) + 'jt';
                        } else {
                            formattedCommAmount = 'Rp ' + commAmount.toLocaleString('id-ID');
                        }
                        document.getElementById('detailCommissionAmount').textContent = formattedCommAmount;

                        // Set status with proper styling
                        const statusElement = document.getElementById('detailStatus');
                        statusElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium';
                        if(transaction.status === 'process') {
                            statusElement.classList.add('bg-amber-100', 'text-amber-800', 'dark:bg-amber-900/30', 'dark:text-amber-200');
                            statusElement.textContent = 'Sedang Proses';
                        } else if(transaction.status === 'success') {
                            statusElement.classList.add('bg-emerald-100', 'text-emerald-800', 'dark:bg-emerald-900/30', 'dark:text-emerald-200');
                            statusElement.textContent = 'Sukses';
                        } else {
                            statusElement.classList.add('bg-red-100', 'text-red-800', 'dark:bg-red-900/30', 'dark:text-red-200');
                            statusElement.textContent = 'Gagal';
                        }

                        document.getElementById('detailUser').textContent = transaction.user?.name || '-';
                        document.getElementById('detailAdmin').textContent = transaction.admin?.name || '-';
                        document.getElementById('detailCreatedAt').textContent = new Date(transaction.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                        document.getElementById('detailConfirmedAt').textContent = transaction.confirmed_at ? new Date(transaction.confirmed_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';
                        document.getElementById('detailCompletedAt').textContent = transaction.completed_at ? new Date(transaction.completed_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';

                        // Handle proof of transfer image
                        const proofSection = document.getElementById('proofSection');
                        const proofImage = document.getElementById('detailProofImage');
                        if(transaction.proof_image) {
                            let imageUrl = transaction.proof_image;
                            if (!transaction.proof_image.startsWith('/storage/') && !transaction.proof_image.startsWith('/public/')) {
                                imageUrl = '/storage/' + transaction.proof_image;
                            }
                            proofImage.src = imageUrl;
                            proofSection.classList.remove('hidden');
                        } else {
                            proofSection.classList.add('hidden');
                        }

                        // Show the modal
                        document.getElementById('detailModal').classList.remove('hidden');
                    } else {
                        alert('Gagal memuat detail transaksi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat detail transaksi');
                });
        }

        // Status Update Modal Functions - Now using custom modal instead of prompt
    </script>

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
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Nama Pelanggan</p>
                                <p id="detailCustomerName" class="font-semibold text-neutral-900 dark:text-white"></p>
                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Nomor Telepon</p>
                                <p id="detailCustomerPhone" class="font-semibold text-neutral-900 dark:text-white"></p>
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
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Status</p>
                                <span id="detailStatus" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"></span>
                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Petugas</p>
                                <p id="detailUser" class="font-semibold text-neutral-900 dark:text-white"></p>
                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Admin</p>
                                <p id="detailAdmin" class="font-semibold text-neutral-900 dark:text-white"></p>
                            </div>
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Dibuat</p>
                                <p id="detailCreatedAt" class="font-semibold text-neutral-900 dark:text-white"></p>
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

    <!-- Status Update Confirmation Modal -->
    <div id="statusUpdateModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeStatusUpdateModal()"></div>
            <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl transition-all dark:bg-neutral-800 w-full max-w-md">
                <div class="border-b border-neutral-200 dark:border-neutral-700 p-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                            <i data-lucide="refresh-cw" class="size-6 text-amber-600 dark:text-amber-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Ubah Status Transaksi</h3>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400" id="statusUpdateSubtitle">Konfirmasi perubahan status</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeStatusUpdateModal()" class="absolute top-4 right-4 text-neutral-400 hover:text-neutral-600 dark:text-neutral-500 dark:hover:text-neutral-300">
                        <i data-lucide="x" class="size-6"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-2">Nama Pelanggan</p>
                            <p id="statusCustomerName" class="font-semibold text-neutral-900 dark:text-white"></p>
                        </div>
                        
                        <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-2">Status Saat Ini</p>
                            <span id="currentStatusBadge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"></span>
                        </div>
                        
                        <div>
                            <label for="newStatusSelect" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Status Baru
                            </label>
                            <select id="newStatusSelect" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="process">Process - Sedang Diproses</option>
                                <option value="success">Success - Berhasil</option>
                                <option value="failed">Failed - Gagal</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-700/50 p-6 flex justify-end gap-3">
                    <button type="button" onclick="closeStatusUpdateModal()" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100 rounded-lg dark:text-neutral-300 dark:hover:bg-neutral-600">
                        Batal
                    </button>
                    <button type="button" onclick="confirmStatusUpdate()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg dark:bg-blue-500 dark:hover:bg-blue-600">
                        Ubah Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables for status update
        let currentTransactionId = null;
        let currentStatus = null;

        // Close modals function
        function closeModals() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Status Update Modal Functions
        function openStatusUpdateModal(transactionId, customerName, currentStatusValue) {
            // Store global variables
            currentTransactionId = transactionId;
            currentStatus = currentStatusValue;
            
            // Update modal content
            document.getElementById('statusCustomerName').textContent = customerName;
            document.getElementById('statusUpdateSubtitle').textContent = `Ubah status untuk transaksi "${customerName}"`;
            
            // Update current status badge
            const currentStatusBadge = document.getElementById('currentStatusBadge');
            currentStatusBadge.textContent = getStatusLabel(currentStatusValue);
            currentStatusBadge.className = getStatusBadgeClass(currentStatusValue);
            
            // Set current status in select
            document.getElementById('newStatusSelect').value = currentStatusValue;
            
            // Show the modal
            document.getElementById('statusUpdateModal').classList.remove('hidden');
        }
        
        function closeStatusUpdateModal() {
            document.getElementById('statusUpdateModal').classList.add('hidden');
            // Reset global variables
            currentTransactionId = null;
            currentStatus = null;
        }
        
        function getStatusLabel(status) {
            const labels = {
                'process': 'Process - Sedang Diproses',
                'success': 'Success - Berhasil',
                'failed': 'Failed - Gagal'
            };
            return labels[status] || status;
        }
        
        function getStatusBadgeClass(status) {
            const classes = {
                'process': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                'success': 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200',
                'failed': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200'
            };
            return `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${classes[status] || 'bg-neutral-100 text-neutral-800 dark:bg-neutral-700 dark:text-neutral-200'}`;
        }
        
        function confirmStatusUpdate() {
            const newStatus = document.getElementById('newStatusSelect').value;
            
            // Validate if status changed
            if (newStatus === currentStatus) {
                alert('Status baru tidak boleh sama dengan status saat ini!');
                return;
            }
            
            // Send PUT request to update status (using POST with _method for compatibility)
            fetch(`/transactions/${currentTransactionId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    status: newStatus,
                    _method: 'PUT'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    // Close modal first
                    closeStatusUpdateModal();
                    // Show success message
                    alert(data.message);
                    // Reload the page to reflect changes
                    location.reload();
                } else {
                    alert('Gagal memperbarui status: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui status');
            });
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const detailModal = document.getElementById('detailModal');
            const statusUpdateModal = document.getElementById('statusUpdateModal');

            if (event.target === detailModal) {
                closeModals();
            }
            if (event.target === statusUpdateModal) {
                closeStatusUpdateModal();
            }
        });
    </script>
</x-layouts::app>
