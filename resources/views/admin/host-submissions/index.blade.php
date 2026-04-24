<x-layouts::app :title="__('Submit Host')">
    <div class="container mx-auto px-4 py-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">
                    {{ __('Submit Host') }}
                    <span class="ml-2 inline-flex items-center rounded-lg bg-indigo-600/10 px-2.5 py-1 text-sm font-semibold text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                        Talent Hunter
                    </span>
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2">
                    Lihat dan proses data submit host (approve / reject) per aplikasi.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 dark:bg-green-900/20 dark:border-green-800">
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

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm p-4">
            <form method="GET" action="{{ route('admin.host-submissions.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Status</label>
                    <select
                        name="status"
                        class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                        <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="process" {{ $currentStatus === 'process' ? 'selected' : '' }}>Seleksi</option>
                        <option value="success" {{ $currentStatus === 'success' ? 'selected' : '' }}>Lolos</option>
                        <option value="failed" {{ $currentStatus === 'failed' ? 'selected' : '' }}>Tidak Lolos</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Per Halaman</label>
                    <select
                        name="per_page"
                        class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                        @foreach([10, 20, 30, 50] as $size)
                            <option value="{{ $size }}" {{ (int) $perPage === $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors"
                    >
                        Terapkan
                    </button>
                    <a
                        href="{{ route('admin.host-submissions.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-neutral-200 hover:bg-neutral-300 dark:bg-neutral-700 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white rounded-lg font-medium text-sm transition-colors"
                    >
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Aplikasi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                ID Host
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Nickname
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                WhatsApp Host
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Formulir
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Submitter
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($submissions as $index => $submission)
                            @php
                                $sale = $submission->saleTransaction;
                                $status = $sale?->status ?? 'process';
                                [$statusLabel, $statusClasses] = match ($status) {
                                    'success' => ['Lolos', 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200'],
                                    'failed' => ['Tidak Lolos', 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200'],
                                    default => ['Seleksi', 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200'],
                                };

                                $serviceName = $submission->service?->name ?? $sale?->service_name ?? '-';
                                $serviceImage = $submission->service?->image ? asset($submission->service->image) : null;
                                $serviceInitial = mb_substr($serviceName ?? '', 0, 1) ?: 'A';
                            @endphp

                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-neutral-900 dark:text-white">
                                        {{ ($submissions->firstItem() ?? 0) + $index }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex size-10 items-center justify-center overflow-hidden rounded-xl border border-neutral-200 bg-neutral-50 text-sm font-semibold text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200">
                                            @if($serviceImage)
                                                <img src="{{ $serviceImage }}" alt="{{ $serviceName }}" class="h-full w-full object-cover" />
                                            @else
                                                {{ $serviceInitial }}
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-neutral-900 dark:text-white truncate">
                                                {{ $serviceName }}
                                            </div>
                                            <div class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                                {{ $sale?->transaction_code ?? 'HOST-SUBMIT' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">
                                    {{ $submission->host_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">
                                    {{ $submission->nickname }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">
                                    <a href="https://wa.me/{{ preg_replace('/\\D+/', '', $submission->whatsapp_number) }}"
                                       target="_blank"
                                       class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        {{ $submission->whatsapp_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $submission->form_filled ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200' : 'bg-neutral-100 dark:bg-neutral-900/30 text-neutral-800 dark:text-neutral-200' }}">
                                        {{ $submission->form_filled ? 'Sudah' : 'Belum' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                        {{ $sale?->user?->name ?? '-' }}
                                    </div>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                        {{ $sale?->user?->email ?? '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-neutral-900 dark:text-white">
                                    {{ $submission->created_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($sale)
                                            <a href="{{ route('sales.show', $sale) }}"
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                               title="Lihat Detail Transaksi">
                                                <i data-lucide="eye" class="size-4"></i>
                                            </a>

                                            @if($status === 'process')
                                                <form method="POST" action="{{ route('sales.approve', $sale) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300" title="Approve (Lolos)">
                                                        <i data-lucide="check-circle" class="size-4"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('sales.reject', $sale) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Reject (Tidak Lolos)">
                                                        <i data-lucide="x-circle" class="size-4"></i>
                                                    </button>
                                                </form>
                                            @elseif($status === 'success')
                                                <form method="POST" action="{{ route('sales.process', $sale) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300" title="Kembalikan ke Seleksi">
                                                        <i data-lucide="refresh-cw" class="size-4"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('sales.reject', $sale) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Reject (Tidak Lolos)">
                                                        <i data-lucide="x-circle" class="size-4"></i>
                                                    </button>
                                                </form>
                                            @elseif($status === 'failed')
                                                <form method="POST" action="{{ route('sales.approve', $sale) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300" title="Approve (Lolos)">
                                                        <i data-lucide="check-circle" class="size-4"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('sales.process', $sale) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300" title="Kembalikan ke Seleksi">
                                                        <i data-lucide="refresh-cw" class="size-4"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="inbox" class="size-16 text-neutral-300 dark:text-neutral-700 mb-4"></i>
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                            Tidak ada data submit host
                                        </p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                            Data submit host yang masuk akan tampil di sini.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($submissions->hasPages())
                <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts::app>
