<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div class="flex items-center justify-between p-6 border-b border-neutral-200 dark:border-neutral-800">
                <h1 class="text-xl font-bold text-neutral-900 dark:text-white">Metode Pembayaran</h1>
                <button wire:click="openCreateModal" class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
                    <i data-lucide="plus" class="size-4"></i>
                    Tambah Metode
                </button>
            </div>

            <div class="p-6">
                @if(session()->has('success'))
                    <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 p-4 dark:bg-emerald-900/20 dark:border-emerald-800">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i data-lucide="check-circle" class="size-5 text-emerald-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Filters -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label for="search" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Cari</label>
                        <input type="text" id="search" wire:model.live.debounce.300ms="search" placeholder="Cari nama, nomor rekening..." 
                               class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                    </div>
                    <div>
                        <label for="filterType" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Tipe</label>
                        <select id="filterType" wire:model.live="filterType"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                            <option value="">Semua Tipe</option>
                            <option value="bank_account">Rekening Bank</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Status</label>
                        <select id="status" wire:model.live="status" 
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button wire:click="$refresh" class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                            <i data-lucide="refresh-cw" class="size-4"></i>
                            Reset
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-neutral-200 dark:border-neutral-800">
                    <table class="w-full">
                        <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Layanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Detail</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                            @forelse($paymentMethods as $method)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($method->service)
                                            <div class="flex items-center gap-3">
                                                <img src="{{ asset($method->service->image) }}" alt="{{ $method->service->name }}" class="size-8 rounded-lg object-cover">
                                                <div class="text-sm">
                                                    <div class="font-medium text-neutral-900 dark:text-white">{{ $method->service->name }}</div>
                                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">ID: {{ $method->service->id }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-xs text-neutral-400 italic">Tidak ada layanan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-neutral-900 dark:text-white">{{ $method->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $method->type === 'bank_account' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' }}">
                                            {{ $method->type === 'bank_account' ? 'Rekening Bank' : 'QRIS' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($method->type === 'bank_account')
                                            <div class="text-sm">
                                                <div class="font-medium text-neutral-900 dark:text-white">{{ $method->account_number }}</div>
                                                <div class="text-xs text-neutral-500 dark:text-neutral-400">a.n. {{ $method->account_holder }}</div>
                                            </div>
                                        @else
                                            @if($method->qr_code_path)
                                                <img src="{{ asset($method->qr_code_path) }}" alt="QRIS" class="w-16 h-16 rounded border border-neutral-200 dark:border-neutral-700">
                                            @else
                                                <span class="text-sm text-neutral-400 dark:text-neutral-500">QRIS tidak tersedia</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $method->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-neutral-100 text-neutral-800 dark:bg-neutral-800 dark:text-neutral-400' }}">
                                            {{ $method->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                        {{ $method->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button wire:click="toggleStatus({{ $method->id }})" class="inline-flex items-center gap-1 rounded-lg border border-neutral-300 bg-white px-3 py-1.5 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                                                <i data-lucide="{{ $method->is_active ? 'toggle-right' : 'toggle-left' }}" class="size-4 {{ $method->is_active ? 'text-emerald-600 dark:text-emerald-400' : 'text-neutral-400' }}"></i>
                                                {{ $method->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                            <button wire:click="edit({{ $method->id }})" class="inline-flex items-center gap-1 rounded-lg border border-neutral-300 bg-white px-3 py-1.5 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                                                <i data-lucide="edit-2" class="size-4"></i>
                                                Edit
                                            </button>
                                            <button wire:click="confirmDelete({{ $method->id }})" class="inline-flex items-center gap-1 rounded-lg border border-red-300 bg-white px-3 py-1.5 text-sm font-medium text-red-700 transition hover:bg-red-50 dark:border-red-700 dark:bg-neutral-900 dark:text-red-300 dark:hover:bg-red-900">
                                                <i data-lucide="trash-2" class="size-4"></i>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="credit-card" class="size-12 text-neutral-400 dark:text-neutral-600 mx-auto mb-3"></i>
                                            <p class="text-sm font-medium text-neutral-900 dark:text-white">Tidak ada metode pembayaran</p>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Belum ada metode pembayaran yang ditambahkan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $paymentMethods->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Create/Edit -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-4 pt-4 sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
            
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                <div class="pointer-events-auto w-screen max-w-md">
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl dark:bg-neutral-900">
                        <div class="flex-1">
                            <div class="border-b border-neutral-200 px-4 py-6 sm:px-6 dark:border-neutral-800">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-medium text-neutral-900 dark:text-white" id="slide-over-title">
                                        {{ $isEditMode ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran' }}
                                    </h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button type="button" wire:click="closeModal" class="relative rounded-md bg-white text-neutral-400 hover:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:text-neutral-200">
                                            <span class="absolute -inset-2.5"></span>
                                            <i data-lucide="x" class="size-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <form wire:submit.prevent="save" class="px-4 py-6 sm:px-6 space-y-6">
                                <!-- Service Selection -->
                                <div>
                                    <label for="service_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Pilih Layanan / Brand</label>
                                    <div class="relative">
                                        <select id="service_id" wire:model="service_id" required
                                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                            <option value="">-- Pilih Layanan --</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}">
                                                    [{{ $service->id }}] {{ $service->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('service_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    
                                    @if($service_id)
                                        @php $selectedService = $services->firstWhere('id', $service_id); @endphp
                                        @if($selectedService)
                                            <div class="mt-3 flex items-center gap-3 p-3 rounded-lg border border-neutral-200 bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-800/50">
                                                <img src="{{ asset($selectedService->image) }}" class="size-10 rounded-lg object-cover border border-neutral-200 dark:border-neutral-700">
                                                <div>
                                                    <p class="text-sm font-bold text-neutral-900 dark:text-white">{{ $selectedService->name }}</p>
                                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">ID Layanan: {{ $selectedService->id }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Nama Metode Pembayaran</label>
                                    <input id="name" type="text" wire:model="name" required autocomplete="name" autofocus
                                           class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Type -->
                                <div>
                                    <label for="type" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Tipe Metode Pembayaran</label>
                                    <select id="type" wire:model.live="type" required
                                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                        <option value="">Pilih Tipe</option>
                                        <option value="bank_account">Rekening Bank</option>
                                        <option value="qris">QRIS</option>
                                    </select>
                                    @error('type')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Account Number -->
                                @if($type === 'bank_account')
                                <div>
                                    <label for="account_number" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Nomor Rekening</label>
                                    <input id="account_number" type="text" wire:model="account_number" autocomplete="account_number"
                                           class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                                    @error('account_number')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                <!-- Account Holder -->
                                @if($type === 'bank_account')
                                <div>
                                    <label for="account_holder" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Nama Pemilik Rekening</label>
                                    <input id="account_holder" type="text" wire:model="account_holder" autocomplete="account_holder"
                                           class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">
                                    @error('account_holder')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                <!-- QR Code -->
                                @if($type === 'qris')
                                <div>
                                    <label for="qr_code" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Gambar QRIS</label>
                                    <input id="qr_code" type="file" wire:model="qr_code" accept="image/*"
                                           class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                    @error('qr_code')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror

                                    @if($qr_code)
                                        <div class="mt-3">
                                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">Pratinjau:</p>
                                            <img src="{{ $qr_code->temporaryUrl() }}" alt="QRIS Preview" class="w-32 h-32 rounded border border-neutral-200 dark:border-neutral-700">
                                        </div>
                                    @elseif($isEditMode && $payment_method_id)
                                        @php
                                            $paymentMethod = \App\Models\PaymentMethod::find($payment_method_id);
                                        @endphp
                                        @if($paymentMethod && $paymentMethod->qr_code_path)
                                        <div class="mt-3">
                                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">QRIS saat ini:</p>
                                            <img src="{{ asset($paymentMethod->qr_code_path) }}" alt="QRIS" class="w-32 h-32 rounded border border-neutral-200 dark:border-neutral-700">
                                        </div>
                                        @endif
                                    @endif

                                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Format: JPEG, PNG. Ukuran maksimal: 2MB. Biarkan kosong jika tidak ingin mengganti.</p>
                                </div>
                                @endif

                                <!-- Is Active -->
                                <div class="flex items-center">
                                    <input type="checkbox" id="is_active" wire:model="is_active" value="1"
                                           class="h-4 w-4 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                    <label for="is_active" class="ml-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Aktif</label>
                                </div>

                                <!-- Buttons -->
                                <div class="flex items-center justify-end gap-3 pt-4">
                                    <button type="button" wire:click="closeModal" class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                                        Batal
                                    </button>
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
                                        <i data-lucide="save" class="size-4"></i>
                                        {{ $isEditMode ? 'Update Metode Pembayaran' : 'Simpan Metode Pembayaran' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Confirmation Modal for Delete -->
    @if($confirmDelete)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-4 pt-4 sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            
            <div class="pointer-events-none fixed inset-0 flex items-center justify-center p-4 sm:items-center sm:p-0">
                <div class="pointer-events-auto relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg dark:bg-neutral-900">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 dark:bg-neutral-900">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10 dark:bg-red-900/30">
                                <i data-lucide="alert-triangle" class="size-6 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-neutral-900 dark:text-white" id="modal-title">
                                    Konfirmasi Hapus
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                        Apakah Anda yakin ingin menghapus metode pembayaran ini? Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-neutral-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 dark:bg-neutral-800">
                        <button wire:click="delete" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            Hapus
                        </button>
                        <button wire:click="cancelDelete" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 sm:mt-0 sm:w-auto dark:bg-neutral-700 dark:text-white dark:ring-neutral-600 dark:hover:bg-neutral-600">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    // Initialize Lucide icons when the component updates
    document.addEventListener('livewire:navigated', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
    
    // Also initialize on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
    
    // Initialize when Livewire updates the DOM
    document.addEventListener('livewire:update', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
</file>