<x-layouts::app :title="__('Manajemen Layanan')">
    <div class="container mx-auto px-4 py-6 space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">
                    Manajemen Layanan
                    @if(request('category') === 'talent_hunter')
                        <span class="ml-2 inline-flex items-center rounded-lg bg-indigo-600/10 px-2.5 py-1 text-sm font-semibold text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                            Talent Hunter
                        </span>
                    @endif
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2">
                    Kelola semua layanan dalam sistem dengan mudah dan efisien
                </p>
            </div>

            <a 
                href="{{ route('services.create', request('category') ? ['category' => request('category')] : []) }}" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-xl font-medium text-sm transition-all duration-200 shadow-lg hover:shadow-xl"
            >
                <i data-lucide="plus" class="size-5"></i>
                <span>{{ request('category') === 'talent_hunter' ? 'Tambah Aplikasi Talent Hunter' : 'Tambah Layanan Baru' }}</span>
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Services -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-50 to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 p-6 shadow-lg">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Layanan</p>
                        <p class="mt-2 text-4xl font-bold text-neutral-900 dark:text-white">{{ $services->total() }}</p>
                        <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                <i data-lucide="trending-up" class="size-3"></i>
                                +{{ rand(1, 10) }}%
                            </span>
                            <span class="text-neutral-500 dark:text-neutral-400 ml-1">dari bulan lalu</span>
                        </p>
                    </div>
                    <div class="rounded-xl bg-gradient-to-br from-neutral-200 to-neutral-300 dark:from-neutral-700 dark:to-neutral-600 p-4 shadow-md">
                        <i data-lucide="package" class="size-8 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Card 2: Active Services -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/30 dark:to-emerald-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Aktif</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-900 dark:text-emerald-100">{{ App\Models\Service::where('status', 'active')->count() }}</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                <i data-lucide="trending-up" class="size-3"></i>
                                +{{ rand(1, 5) }}%
                            </span>
                            <span class="text-neutral-500 dark:text-neutral-400 ml-1">dari bulan lalu</span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-emerald-400 dark:bg-emerald-600 p-3">
                        <i data-lucide="check-circle" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Card 3: Inactive Services -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/30 dark:to-amber-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-700 dark:text-amber-300">Tidak Aktif</p>
                        <p class="mt-2 text-3xl font-bold text-amber-900 dark:text-amber-100">{{ App\Models\Service::where('status', 'inactive')->count() }}</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400">
                                <i data-lucide="trending-down" class="size-3"></i>
                                -{{ rand(1, 3) }}%
                            </span>
                            <span class="text-neutral-500 dark:text-neutral-400 ml-1">dari bulan lalu</span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-amber-400 dark:bg-amber-600 p-3">
                        <i data-lucide="x-circle" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Card 4: Draft Services -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Draft</p>
                        <p class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100">{{ App\Models\Service::where('status', 'draft')->count() }}</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                <i data-lucide="file" class="size-3"></i>
                                {{ rand(0, 5) }} baru
                            </span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-blue-400 dark:bg-blue-600 p-3">
                        <i data-lucide="file-text" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-lg">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <i data-lucide="filter" class="size-5 text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Filter & Pencarian</h3>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Temukan layanan yang Anda butuhkan dengan cepat</p>
                    </div>
                </div>
            </div>

			            <div class="p-4">
			                <form method="GET" action="{{ route('services.index') }}" class="space-y-4">
			                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
			                        <!-- Tipe Data -->
			                        <div class="space-y-2">
			                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tipe Data</label>
			                            <select
		                                name="category"
	                                onchange="this.form.submit()"
	                                class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
	                            >
	                                <option value="">Semua</option>
	                                <option value="talent_hunter" {{ request('category') === 'talent_hunter' ? 'selected' : '' }}>Talent Hunter</option>
	                                <option value="reseller_coin" {{ request('category') === 'reseller_coin' ? 'selected' : '' }}>Reseller Koin</option>
		                            </select>
		                        </div>

		                        <!-- Status -->
		                        <div class="space-y-2">
	                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Status</label>
	                            <select
	                                name="status"
	                                onchange="this.form.submit()"
	                                class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
	                            >
	                                <option value="">Semua Status</option>
	                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
	                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
	                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
		                            </select>
		                        </div>

			                        <!-- Search -->
			                        <div class="space-y-2">
			                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Cari Layanan</label>
			                            <input
			                                type="search"
			                                name="search"
			                                placeholder="Cari nama atau deskripsi..."
			                                value="{{ request('search') }}"
			                                inputmode="search"
			                                enterkeyhint="search"
			                                autocapitalize="none"
			                                autocorrect="off"
			                                class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
			                            />
			                        </div>
			                    </div>

			                    <div class="border-t border-neutral-200 dark:border-neutral-700 pt-4"></div>

				                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-end">
				                        <div class="w-full md:max-w-sm space-y-2">
				                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tanggal Dibuat</label>
				                            <input
				                                type="date"
				                                name="created_date"
				                                value="{{ request('created_date', request('start_date')) }}"
				                                class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
				                            />
				                        </div>

				                        <div class="flex flex-wrap items-center gap-2 md:justify-end">
				                            <button
				                                type="submit"
				                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors"
				                            >
			                                Cari
			                            </button>
			                            <button
			                                type="submit"
			                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors"
			                            >
			                                Terapkan
			                            </button>
			                            <a
			                                href="{{ route('services.index', request('category') ? ['category' => request('category')] : []) }}"
			                                class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-700 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-200 rounded-lg font-medium text-sm transition-colors"
			                            >
			                                Reset
			                            </a>
			                        </div>
			                    </div>
			                </form>
			            </div>
			        </div>

        <!-- Services Table -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-neutral-50 to-neutral-100 dark:from-neutral-900 dark:to-neutral-800 border-b border-neutral-200 dark:border-neutral-700">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                No
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Gambar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Nama Aplikasi
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Tipe Data
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Komisi
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                WhatsApp
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Tanggal Dibuat
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($services as $index => $service)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-neutral-900 dark:text-white">{{ $services->firstItem() + $index }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($service->image)
                                        <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="w-16 h-16 rounded-lg object-cover mx-auto border border-neutral-200 dark:border-neutral-700">
                                    @else
                                        <div class="w-16 h-16 rounded-lg bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center mx-auto border border-neutral-200 dark:border-neutral-700">
                                            <i data-lucide="image" class="size-6 text-neutral-400 dark:text-neutral-600"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">{{ $service->name }}</div>
                                    @if($service->whatsapp_number)
                                        <div class="mt-1 flex items-center gap-1 text-xs text-green-600 dark:text-green-400">
                                            <svg class="size-3" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            +{{ $service->whatsapp_number }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $serviceTypeLabel = match ($service->category) {
                                            'talent_hunter' => 'Talent Hunter',
                                            'reseller_coin', null, '' => 'Reseller Koin',
                                            default => ucfirst(str_replace('_', ' ', (string) $service->category)),
                                        };

                                        $serviceTypeClasses = match ($service->category) {
                                            'talent_hunter' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300',
                                            default => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $serviceTypeClasses }}">
                                        {{ $serviceTypeLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($service->status === 'active')
                                            bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200
                                        @elseif($service->status === 'inactive')
                                            bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200
                                        @else
                                            bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200
                                        @endif">
                                        {{ ucfirst($service->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                        {{ number_format($service->commission_rate, 2) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($service->whatsapp_number)
                                        <a href="https://wa.me/{{ $service->whatsapp_number }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-green-600 dark:text-green-400 hover:underline">
                                            <svg class="size-4" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            +{{ $service->whatsapp_number }}
                                        </a>
                                    @else
                                        <span class="text-xs text-neutral-400 dark:text-neutral-600">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-neutral-900 dark:text-white">
                                        {{ $service->created_at->setTimezone('Asia/Jakarta')->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                        {{ $service->created_at->setTimezone('Asia/Jakarta')->format('H:i') }} WIB
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('services.show', $service) }}"
                                            class="inline-flex items-center justify-center p-2 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-800/50 text-blue-600 dark:text-blue-400 rounded-xl transition-all duration-200 hover:scale-105"
                                            title="Lihat Detail"
                                        >
                                            <i data-lucide="eye" class="size-4"></i>
                                        </a>
                                        <a
                                            href="{{ route('services.edit', $service) }}"
                                            class="inline-flex items-center justify-center p-2 bg-amber-100 dark:bg-amber-900/30 hover:bg-amber-200 dark:hover:bg-amber-800/50 text-amber-600 dark:text-amber-400 rounded-xl transition-all duration-200 hover:scale-105"
                                            title="Edit"
                                        >
                                            <i data-lucide="pencil" class="size-4"></i>
                                        </a>
                                        <button
                                            type="button"
                                            onclick="shareToWhatsApp('{{ $service->name }}', '{{ $service->image ?? '' }}')"
                                            class="inline-flex items-center justify-center p-2 bg-green-100 dark:bg-green-900/30 hover:bg-green-200 dark:hover:bg-green-800/50 text-green-600 dark:text-green-400 rounded-lg transition-colors"
                                            title="Share ke WhatsApp"
                                        >
                                            <svg viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            onclick="openDeleteModal({{ $service->id }})"
                                            class="inline-flex items-center justify-center p-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-800/50 text-red-600 dark:text-red-400 rounded-lg transition-colors"
                                            title="Hapus"
                                        >
                                            <i data-lucide="trash" class="size-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="package" class="size-16 text-neutral-300 dark:text-neutral-700 mb-4"></i>
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                            Belum ada layanan
                                        </p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                            Mulai dengan menambahkan layanan baru
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($services->hasPages())
                <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
                    {{ $services->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 hidden">
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-start gap-3 mb-4">
                <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                    <i data-lucide="alert-triangle" class="size-5 text-red-600 dark:text-red-400"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        Hapus Layanan?
                    </h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        Apakah Anda yakin ingin menghapus layanan ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors"
                >
                    Batal
                </button>
                <form
                    method="POST"
                    id="delete-form"
                    class="inline"
                >
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white rounded-lg font-medium text-sm transition-colors"
                    >
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Wait for jQuery and Toastr to be loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            function initLucideIcons() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }

            initLucideIcons();
            setTimeout(initLucideIcons, 100);

            // Show Toastr notification if there's a success message
            @if(session('success'))
                if (typeof $ !== 'undefined' && typeof toastr !== 'undefined') {
                    // Create custom HTML with Lucide icon
                    const iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`;
                    
                    toastr.success(iconSvg + '{{ session('success') }}', 'Sukses', {
                        escapeHtml: false,
                        timeOut: 5000
                    });
                }
            @endif

            @if(session('error'))
                if (typeof $ !== 'undefined' && typeof toastr !== 'undefined') {
                    toastr.error('{{ session('error') }}', 'Error');
                }
            @endif
        });

        // Delete modal functions
        let currentDeleteId = null;

        function openDeleteModal(serviceId) {
            currentDeleteId = serviceId;
            document.getElementById('delete-form').action = `/services/${serviceId}`;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentDeleteId = null;
        }

        // Close modal when clicking outside
        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Share to WhatsApp function
        function shareToWhatsApp(serviceName, serviceImage) {
            const appUrl = window.location.origin + '/app/job';
            const message = `Halo, saya ingin membagikan layanan dari Rigel Agency:%0A%0A` +
                `📱 *${encodeURIComponent(serviceName)}*%0A%0A` +
                `Top up murah dan terpercaya hanya di Rigel Agency!%0A%0A` +
                `🔗 Order sekarang: ${encodeURIComponent(appUrl)}`;

            window.open(`https://wa.me/?text=${message}`, '_blank');
        }
    </script>
</x-layouts::app>
