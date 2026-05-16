<x-layouts::app :title="'Pengaturan Sistem'">
    <div class="container mx-auto px-4 py-6 space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Pengaturan Sistem
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Atur persentase komisi dan minimum nominal transaksi
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

        @if(session('error'))
            <div class="rounded-lg bg-red-50 border border-red-200 p-4 dark:bg-red-900/20 dark:border-red-800">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 p-4 dark:bg-red-900/20 dark:border-red-800">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">
                            Terjadi kesalahan validasi:
                        </p>
                        <ul class="text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Service Commission Settings -->
        <div class="rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <form method="POST" action="{{ route('admin.settings.service-commissions.update') }}">
                @csrf

                <div class="border-b border-neutral-200 p-6 dark:border-neutral-800">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">
                        Pengaturan Komisi & WhatsApp per Layanan
                    </h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Atur persentase komisi dan nomor WhatsApp yang berbeda untuk setiap layanan/brand
                    </p>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($services as $service)
                            <div class="flex flex-wrap items-center gap-4 p-4 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800/50">
                                <!-- Service Image -->
                                <div class="flex-shrink-0">
                                    @if($service->image)
                                        <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="w-12 h-12 rounded-lg object-cover border border-neutral-200 dark:border-neutral-700">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center border border-neutral-200 dark:border-neutral-700">
                                            <i data-lucide="package" class="size-6 text-neutral-400 dark:text-neutral-600"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Service Info -->
                                <div class="flex-1 min-w-[150px]">
                                    <p class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $service->name }}</p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">ID: {{ $service->id }}</p>
                                </div>

                                <!-- Commission Input -->
                                <div class="flex-shrink-0 w-28">
                                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Komisi (%)</label>
                                    <div class="relative">
                                        <input type="number"
                                               name="commissions[{{ $service->id }}]"
                                               value="{{ old('commissions.' . $service->id, $service->commission_rate) }}"
                                               step="0.01"
                                               min="0"
                                               max="100"
                                               class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:focus:border-white dark:focus:ring-white"
                                               required>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <span class="text-neutral-400 text-xs">%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Minimum Nominal Input -->
                                <div class="flex-shrink-0 w-32">
                                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Min. Nominal (Rp)</label>
                                    <input type="number"
                                           name="minimum_nominals[{{ $service->id }}]"
                                           value="{{ old('minimum_nominals.' . $service->id, $service->minimum_nominal ?? 15000) }}"
                                           step="1000"
                                           min="0"
                                           class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:focus:border-white dark:focus:ring-white"
                                           required>
                                </div>

                                <!-- WhatsApp Input -->
                                <div class="flex-shrink-0 w-40">
                                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">WhatsApp</label>
                                    <div class="flex">
                                        <div class="flex-shrink-0 flex items-center justify-center px-2 py-1.5 bg-neutral-100 dark:bg-neutral-700 border border-r-0 border-neutral-300 dark:border-neutral-600 rounded-l text-xs">
                                            <span class="text-neutral-600 dark:text-neutral-400 whitespace-nowrap">+62</span>
                                        </div>
                                        <input type="text"
                                               name="whatsapp_numbers[{{ $service->id }}]"
                                               value="{{ old('whatsapp_numbers.' . $service->id, $service->whatsapp_number ? preg_replace('/^(\+62|62)/', '', $service->whatsapp_number) : '') }}"
                                               placeholder="81234567890"
                                                class="flex-1 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 px-2 py-1.5 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-white rounded-r rounded-l-none w-24">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i data-lucide="package" class="size-12 text-neutral-300 dark:text-neutral-700 mx-auto mb-3"></i>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Belum ada layanan yang terdaftar</p>
                                <a href="{{ route('services.create') }}" class="mt-2 inline-flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                    <i data-lucide="plus" class="size-4"></i>
                                    Tambah Layanan
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Info Section -->
                <div class="border-b border-neutral-200 bg-neutral-50 p-6 dark:border-neutral-800 dark:bg-neutral-800/50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-neutral-900 dark:text-white">
                                Catatan Penting
                            </h3>
                            <div class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Setiap layanan dapat memiliki persentase komisi dan nomor WhatsApp yang berbeda</li>
                                    <li>Komisi akan otomatis dihitung saat user melakukan top up</li>
                                    <li>Minimum nominal: Nominal minimal transaksi untuk setiap layanan (default: Rp 15.000)</li>
                                    <li>WhatsApp: Isi tanpa +62 (contoh: 81234567890)</li>
                                    <li>Pastikan data sudah benar sebelum menyimpan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="p-6 flex justify-end gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Pengaturan Layanan
                    </button>
                </div>

                @php
                    $formAction = route('admin.settings.service-commissions.update');
                @endphp
                <script>
                    // Auto-add 62 prefix to WhatsApp numbers when form is submitted
                    document.querySelector('form[action="{{ $formAction }}"]').addEventListener('submit', function(e) {
                        const whatsappInputs = document.querySelectorAll('input[name^="whatsapp_numbers["]');
                        whatsappInputs.forEach(function(input) {
                            const inputValue = input.value.trim();
                            if (inputValue && !inputValue.startsWith('62')) {
                                // Remove any leading zeros and add 62
                                input.value = '62' + inputValue.replace(/^0+/, '');
                            }
                        });
                    });
                </script>
            </form>
	        </div>

	        <!-- Payment Information Settings (Separate Section) -->
	        @if(auth()->user()->isAdmin())
	        <div class="rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
	            <div class="border-b border-neutral-200 p-6 dark:border-neutral-800">
	                <div class="flex items-center justify-between mb-4">
	                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
	                        Informasi Pembayaran
	                    </h3>
                    <button type="button" onclick="togglePaymentForm()" class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
                        <i data-lucide="plus" class="size-4"></i>
                        <span>Tambah Metode Pembayaran</span>
                    </button>
                </div>

                <!-- Add Payment Method Form (Hidden by default) -->
                <div id="paymentForm" class="hidden mb-6 p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700">
                    <h4 class="text-sm font-semibold text-neutral-900 dark:text-white mb-4">Tambah Metode Pembayaran Baru</h4>
                    <form method="POST" action="{{ route('admin.settings.payment-methods.store') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <!-- Service Selection -->
                        <div>
                            <label for="service_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Pilih Layanan / Brand
                            </label>
                            <select id="service_id" name="service_id" onchange="updateServicePreview()" class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white" required>
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" data-image="{{ asset($service->image) }}" data-name="{{ $service->name }}">
                                        [{{ $service->id }}] {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <!-- Service Preview -->
                            <div id="servicePreview" class="hidden mt-3 flex items-center gap-4 p-3 rounded-lg border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-800 shadow-sm">
                                <img id="previewImage" src="" class="size-12 rounded-lg object-cover border border-neutral-200 dark:border-neutral-700">
                                <div>
                                    <p id="previewName" class="text-sm font-bold text-neutral-900 dark:text-white"></p>
                                    <p id="previewId" class="text-xs text-neutral-500 dark:text-neutral-400"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Type -->
                        <div>
                            <label for="payment_type" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Tipe Pembayaran
                            </label>
                            <select id="payment_type" name="type" onchange="togglePaymentFields()" class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white" required>
                                <option value="">Pilih tipe</option>
                                <option value="bank_account">Rekening Bank</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="payment_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Nama Bank / QRIS
                            </label>
                            <input type="text" id="payment_name" name="name" class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white" placeholder="Contoh: BCA, Mandiri, QRIS" required>
                        </div>

                        <!-- Bank Account Fields -->
                        <div id="bankFields" class="hidden space-y-4">
                            <div>
                                <label for="account_number" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Nomor Rekening
                                </label>
                                <input type="text" id="account_number" name="account_number" class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white" placeholder="123-456-7890">
                            </div>
                            <div>
                                <label for="account_holder" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Nama Pemilik
                                </label>
                                <input type="text" id="account_holder" name="account_holder" class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white" placeholder="a.n. PT Rigel Agency">
                            </div>
                        </div>

                        <!-- QRIS Fields -->
                        <div id="qrisFields" class="hidden space-y-4">
                            <div>
                                <label for="nmid" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    NMID (Nomor Merchant ID)
                                </label>
                                <input type="text" id="nmid" name="nmid" class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white" placeholder="ID1234567890123">
                            </div>
                            <div>
                                <label for="qr_code" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Upload QR Code (Opsional)
                                </label>
                                <input type="file" id="qr_code" name="qr_code" accept="image/*" class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Format: JPEG, PNG, JPG, GIF (Max: 2MB)</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="togglePaymentForm()" class="inline-flex items-center rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                                Batal
                            </button>
                            <button type="submit" class="inline-flex items-center rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Payment Methods List -->
                <div class="space-y-4">
                    @forelse($paymentMethods as $method)
                        <div class="p-4 rounded-xl bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700">
                            <!-- Service Association Header -->
                            @if($method->service)
                                <div class="flex items-center gap-4 mb-4 pb-4 border-b border-neutral-200 dark:border-neutral-800">
                                    @if($method->service && $method->service->image && file_exists(public_path($method->service->image)))
                                        <img src="{{ asset($method->service->image) }}" alt="{{ $method->service->name }}" class="size-10 rounded-lg object-cover shadow-sm border border-neutral-200 dark:border-neutral-700">
                                    @else
                                        <div class="size-10 rounded-lg bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center border border-neutral-200 dark:border-neutral-700">
                                            <i data-lucide="package" class="size-5 text-neutral-400 dark:text-neutral-500"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Layanan / Brand</p>
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-neutral-200 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">ID: {{ $method->service?->id ?? 'N/A' }}</span>
                                        </div>
                                        <p class="text-sm font-bold text-neutral-900 dark:text-white">{{ $method->service?->name ?? 'Tidak ada layanan' }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg {{ $method->type === 'bank_account' ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-emerald-100 dark:bg-emerald-900/30' }} flex items-center justify-center">
                                    @if($method->type === 'bank_account')
                                        <span class="text-sm font-bold {{ $method->name === 'BCA' || $method->name === 'BRI' ? 'text-blue-700 dark:text-blue-300' : ($method->name === 'Mandiri' ? 'text-yellow-700 dark:text-yellow-300' : 'text-neutral-700 dark:text-neutral-300') }}">
                                            {{ strtoupper(substr($method->name, 0, 3)) }}
                                        </span>
                                    @elseif($method->qr_code_path)
                                        <img src="{{ asset($method->qr_code_path) }}?v={{ time() }}" alt="QRIS" class="size-10 object-cover rounded cursor-pointer hover:opacity-80 transition" onclick="openQrisModal('{{ asset($method->qr_code_path) }}', '{{ $method->name }}')">
                                    @else
                                        <i data-lucide="qr-code" class="size-6 text-emerald-700 dark:text-emerald-300"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $method->name }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $method->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-neutral-100 text-neutral-800 dark:bg-neutral-800 dark:text-neutral-400' }}">
                                            {{ $method->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </div>
                                    @if($method->type === 'bank_account')
                                        <p class="text-lg font-bold text-neutral-900 dark:text-white mt-1">{{ $method->account_number }}</p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">a.n. {{ $method->account_holder }}</p>
                                    @else
                                        @if($method->nmid)
                                            <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mt-1">NMID: {{ $method->nmid }}</p>
                                        @endif
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('admin.settings.payment-methods.toggle', $method) }}" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="p-2 rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition" title="{{ $method->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i data-lucide="{{ $method->is_active ? 'toggle-right' : 'toggle-left' }}" class="size-4 {{ $method->is_active ? 'text-emerald-600 dark:text-emerald-400' : 'text-neutral-400' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('payment-methods.edit', $method) }}" class="p-2 rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition" title="Edit">
                                        <i data-lucide="pencil" class="size-4 text-neutral-600 dark:text-neutral-400"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.settings.payment-methods.delete', $method) }}" class="inline" id="delete-form-{{ $method->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openDeleteModal({{ $method->id }}, '{{ $method->name }}')" class="p-2 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition" title="Hapus">
                                            <i data-lucide="trash-2" class="size-4 text-red-600 dark:text-red-400"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-dashed border-neutral-300 dark:border-neutral-700">
                            <i data-lucide="credit-card" class="size-12 text-neutral-400 dark:text-neutral-600 mx-auto mb-3"></i>
                            <p class="text-sm font-medium text-neutral-900 dark:text-white">Belum ada metode pembayaran</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Klik tombol "Tambah Metode Pembayaran" untuk menambahkan rekening bank atau QRIS</p>
                        </div>
                    @endforelse
	                </div>
	            </div>
	        </div>
	        @endif
	    </div>

	    @if(auth()->user()->isAdmin())
	    <script>
	        // Update service preview when dropdown changes
	        function updateServicePreview() {
	            const select = document.getElementById('service_id');
	            const preview = document.getElementById('servicePreview');
	            const image = document.getElementById('previewImage');
            const name = document.getElementById('previewName');
            const idText = document.getElementById('previewId');
            
            if (select.value) {
                const selectedOption = select.options[select.selectedIndex];
                const imageUrl = selectedOption.getAttribute('data-image');
                const serviceName = selectedOption.getAttribute('data-name');
                
                image.src = imageUrl;
                name.textContent = serviceName;
                idText.textContent = 'ID Layanan: ' + select.value;
                
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }

        // Toggle payment form visibility
        function togglePaymentForm() {
            const form = document.getElementById('paymentForm');
            form.classList.toggle('hidden');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Toggle payment fields based on type
        function togglePaymentFields() {
            const type = document.getElementById('payment_type').value;
            const bankFields = document.getElementById('bankFields');
            const qrisFields = document.getElementById('qrisFields');

            if (type === 'bank_account') {
                bankFields.classList.remove('hidden');
                qrisFields.classList.add('hidden');
            } else if (type === 'qris') {
                bankFields.classList.add('hidden');
                qrisFields.classList.remove('hidden');
            } else {
                bankFields.classList.add('hidden');
                qrisFields.classList.add('hidden');
            }
        }

        // Form validation and submission
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Add form validation
            const paymentForm = document.querySelector('form[action*="payment-methods"]');
            if (paymentForm) {
                paymentForm.addEventListener('submit', function(e) {
                    const formData = new FormData(this);
                    const type = formData.get('type');
                    const name = formData.get('name');

                    if (!type || !name) {
                        alert('Mohon lengkapi semua field yang diperlukan');
                        e.preventDefault();
                        return false;
                    }

                    if (type === 'bank_account') {
                        const accountNumber = formData.get('account_number');
                        const accountHolder = formData.get('account_holder');
                        if (!accountNumber || !accountHolder) {
                            alert('Mohon lengkapi nomor rekening dan nama pemilik');
                            e.preventDefault();
                            return false;
                        }
                    }
                });
            }
        });

        // QRIS Modal Functions
        function openQrisModal(imageUrl, methodName) {
            const modal = document.getElementById('qrisModal');
            const modalImg = document.getElementById('modalQrisImage');
            const modalTitle = document.getElementById('modalQrisTitle');
            const downloadBtn = document.getElementById('downloadQrisBtn');
            
            modalTitle.textContent = 'Preview QRIS - ' + methodName;
            modalImg.src = imageUrl;
            downloadBtn.href = imageUrl;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeQrisModal() {
            const modal = document.getElementById('qrisModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('qrisModal');
            if (e.target === modal) {
                closeQrisModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQrisModal();
            }
        });

        // Delete Modal Functions
        let deleteFormId = null;

        function openDeleteModal(methodId, methodName) {
            deleteFormId = 'delete-form-' + methodId;
            const modal = document.getElementById('deleteModal');
            const modalName = document.getElementById('deleteMethodName');
            
            modalName.textContent = methodName;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            deleteFormId = null;
        }

        function confirmDelete() {
            if (deleteFormId) {
                const form = document.getElementById(deleteFormId);
                if (form) {
                    // Show loading state
                    const deleteBtn = document.getElementById('confirmDeleteBtn');
                    deleteBtn.innerHTML = `
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    `;
                    deleteBtn.disabled = true;
                    deleteBtn.classList.add('opacity-75', 'cursor-not-allowed');
                    deleteBtn.classList.remove('hover:bg-red-700', 'dark:hover:bg-red-500');
                    
                    form.submit();
                }
            }
        }

        // Close delete modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('deleteModal');
            if (e.target === modal) {
                closeDeleteModal();
            }
        });
    </script>

    <!-- QRIS Preview Modal -->
    <div id="qrisModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4" onclick="closeQrisModal()">
        <div class="relative max-w-2xl max-h-[90vh] overflow-auto bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl" onclick="event.stopPropagation()">
            <!-- Close Button -->
            <button onclick="closeQrisModal()" class="absolute top-4 right-4 p-2 rounded-full bg-neutral-100 dark:bg-neutral-800 hover:bg-neutral-200 dark:hover:bg-neutral-700 transition z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-neutral-600 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <!-- Modal Header -->
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-800">
                <h3 id="modalQrisTitle" class="text-xl font-bold text-neutral-900 dark:text-white"></h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Scan QR Code untuk pembayaran</p>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 flex items-center justify-center bg-neutral-50 dark:bg-neutral-950">
                <img id="modalQrisImage" src="" alt="QRIS Full Preview" class="max-w-full max-h-[60vh] object-contain rounded-lg shadow-lg">
            </div>
            
            <!-- Modal Footer -->
            <div class="p-6 border-t border-neutral-200 dark:border-neutral-800 flex justify-center gap-3">
                <button onclick="closeQrisModal()" class="px-6 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300 font-medium hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                    Tutup
                </button>
                <a id="downloadQrisBtn" href="" download="qris-code.png" class="px-6 py-2.5 rounded-lg bg-neutral-900 dark:bg-white text-white dark:text-neutral-900 font-medium hover:bg-neutral-800 dark:hover:bg-neutral-200 transition inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download
                </a>
            </div>
	        </div>
	    </div>

	    <!-- Delete Confirmation Modal -->
	    <div id="deleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4" onclick="closeDeleteModal()">
        <div class="relative w-full max-w-md bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
            <!-- Icon Header -->
            <div class="flex justify-center pt-8 pb-4">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            
            <!-- Modal Content -->
            <div class="px-6 pb-6 text-center">
                <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-2">Hapus Metode Pembayaran?</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                    Anda akan menghapus:
                </p>
                <p class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">
                    "<span id="deleteMethodName"></span>"
                </p>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <p class="text-sm text-red-700 dark:text-red-400">
                        <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Metode pembayaran akan dihapus secara permanen dari sistem.
                    </p>
                </div>
            </div>
            
            <!-- Modal Footer Buttons -->
            <div class="flex gap-3 px-6 pb-6">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300 font-medium hover:bg-neutral-50 dark:hover:bg-neutral-800 transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </button>
                <button onclick="confirmDelete()" id="confirmDeleteBtn" class="flex-1 px-4 py-2.5 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700 dark:hover:bg-red-500 transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Ya, Hapus
                </button>
            </div>
	        </div>
	    </div>
	    @endif
</x-layouts::app>
