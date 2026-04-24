<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="/app/job" class="flex items-center gap-1 text-sm font-medium text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
            <i data-lucide="chevron-left" class="size-4"></i>
            Batal
        </a>
        @php
            // Get service-specific WhatsApp number, fallback to global setting
            $whatsappNumber = isset($service) && $service->whatsapp_number 
                ? $service->whatsapp_number 
                : \App\Models\Setting::getWhatsAppNumber();
        @endphp
        <a href="https://wa.me/{{ $whatsappNumber }}?text={{ \App\Models\Setting::getWhatsAppMessageTemplate() }}"
           class="inline-flex items-center gap-2 rounded-full bg-green-500 px-3 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
            <span>Butuh bantuan?</span>
        </a>
    </div>

    @php
        // Minimum nominal from service, fallback to default 15000
        $minimumNominal = isset($service) && $service->minimum_nominal 
            ? $service->minimum_nominal 
            : 15000;
    @endphp

    {{-- Service Image Display --}}
    @if(isset($service) && $service)
        <div class="flex flex-col items-center justify-center py-6">
            @if($service->image)
                <div class="relative mb-4 block aspect-square w-36 overflow-hidden rounded-xl border border-neutral-200 shadow-sm bg-white p-2 dark:border-neutral-800 dark:bg-neutral-900">
                    <img src="{{ asset($service->image) }}"
                         alt="{{ $service->name }}"
                         class="h-full w-full object-cover rounded-lg">
                </div>
            @else
                <div class="relative mb-4 flex aspect-square w-36 items-center justify-center overflow-hidden rounded-xl border border-neutral-200 shadow-sm bg-gradient-to-br from-indigo-500 to-purple-600 dark:border-neutral-800">
                    <span class="text-3xl font-bold text-white">{{ substr($service->name, 0, 1) }}</span>
                </div>
            @endif
            <h3 class="text-center text-lg font-semibold text-neutral-900 dark:text-white">
                {{ $service->name }}
            </h3>
        </div>
    @endif

    {{-- Form Section (NOW ABOVE PAYMENT METHODS) --}}
    <form class="space-y-6">
        {{-- Section Title --}}
        <div class="flex items-center gap-2">
            <h3 class="font-semibold text-neutral-900 dark:text-white">
                Masukan Data Akun
            </h3>
        </div>

        {{-- Input Fields --}}
        <div class="space-y-4">
            {{-- ID Pengguna --}}
            <div class="relative">
                <input type="number"
                       id="user_id"
                       placeholder="ID Pengguna"
                       oninput="validateInput(this)"
                       class="w-full rounded-full border-2 border-red-400 bg-white px-6 py-3 pr-10 text-center text-sm text-neutral-900 shadow-sm placeholder-neutral-500 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 transition-colors duration-200">
                <div class="input-icon absolute right-4 top-1/2 -translate-y-1/2 hidden">
                    <i data-lucide="check-circle" class="size-5 text-green-500"></i>
                </div>
            </div>

            {{-- Nickname --}}
            <div class="relative">
                <input type="text"
                       id="nickname"
                       placeholder="Nickname Aplikasi"
                       oninput="validateInput(this)"
                       class="w-full rounded-full border-2 border-red-400 bg-white px-6 py-3 pr-10 text-center text-sm text-neutral-900 shadow-sm placeholder-neutral-500 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 transition-colors duration-200">
                <div class="input-icon absolute right-4 top-1/2 -translate-y-1/2 hidden">
                    <i data-lucide="check-circle" class="size-5 text-green-500"></i>
                </div>
            </div>

            {{-- Nominal --}}
            <div class="relative">
                <input type="text"
                       id="nominal"
                       placeholder="Rp {{ number_format($minimumNominal, 0, ',', '.') }}"
                       oninput="formatNominal(this); validateInput(this)"
                       class="w-full rounded-full border-2 border-red-400 bg-white px-6 py-3 pr-10 text-center text-sm text-neutral-900 shadow-sm placeholder-neutral-500 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 transition-colors duration-200">
                <div class="input-icon absolute right-4 top-1/2 -translate-y-1/2 hidden">
                    <i data-lucide="check-circle" class="size-5 text-green-500"></i>
                </div>
                <p id="nominal_note" class="mt-1.5 text-xs text-red-500 dark:text-red-400 text-center hidden">
                    Minimal Rp {{ number_format($minimumNominal, 0, ',', '.') }}
                </p>
            </div>

            {{-- Upload Area (Centered) --}}
            <div id="upload_area" class="relative flex w-full flex-col items-center justify-center rounded-2xl border-2 border-dashed border-neutral-300 bg-neutral-50 py-8 text-center transition hover:bg-neutral-100 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                <div class="mb-2 rounded-full bg-white p-3 shadow-sm dark:bg-neutral-800">
                    <i data-lucide="upload-cloud" class="size-6 text-neutral-500 dark:text-neutral-400"></i>
                </div>
                <p class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
                    Upload Bukti Transfer
                </p>
                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                    Drag & drop atau klik untuk memilih file
                </p>
                <input type="file" id="proof_file" accept="image/*" class="absolute inset-0 cursor-pointer opacity-0" onchange="previewFile(this)">
            </div>

            {{-- Preview Image --}}
            <div id="preview_container" class="hidden">
                <div class="relative rounded-xl border border-neutral-200 bg-neutral-50 p-2 dark:border-neutral-700 dark:bg-neutral-900">
                    <img id="preview_image" class="w-full rounded-lg object-cover" style="max-height: 200px;">
                    <button type="button" onclick="removePreview()" class="absolute -top-2 -right-2 flex size-6 items-center justify-center rounded-full bg-red-500 text-white shadow-md hover:bg-red-600">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                    <div class="mt-2 flex items-center justify-center gap-1.5 text-xs text-neutral-500 dark:text-neutral-400">
                        <i data-lucide="check-circle" class="size-3.5 text-green-500"></i>
                        <span>Bukti transfer berhasil diunggah</span>
                    </div>
                </div>
            </div>

        </div>
    </form>

    {{-- Payment Info (NOW BELOW FORM) --}}
    <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
        <h4 class="mb-4 text-center text-sm font-semibold text-neutral-900 dark:text-white">
            Metode Pembayaran
        </h4>

        <div class="space-y-3">
            @if(isset($paymentMethods) && $paymentMethods->count() > 0)
                @foreach($paymentMethods as $method)
                    {{-- Bank Account Type --}}
                    @if($method->type === 'bank_account')
                        @php
                            $bgColors = [
                                'bca' => 'bg-blue-800',
                                'bni' => 'bg-orange-600',
                                'bri' => 'bg-blue-600',
                                'mandiri' => 'bg-yellow-500',
                                'dana' => 'bg-sky-500',
                                'gopay' => 'bg-green-500',
                                'ovo' => 'bg-purple-500',
                                'shopeepay' => 'bg-orange-500',
                            ];
                            $color = $bgColors[strtolower($method->name)] ?? 'bg-neutral-700';
                        @endphp

                        <div class="payment-method-item flex items-center justify-between rounded-lg border-2 border-neutral-100 bg-neutral-50 p-3 cursor-pointer transition-all duration-200 hover:border-orange-300 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:border-orange-400" 
                             onclick="selectPaymentMethod(this, '{{ $method->name }}', '{{ $method->account_number }}', 'bank')"
                             data-method-name="{{ $method->name }}"
                             data-method-number="{{ $method->account_number }}"
                             data-method-type="bank">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-16 items-center justify-center rounded shadow-sm {{ $color }}">
                                    <span class="font-bold text-white text-xs">{{ strtoupper(substr($method->name, 0, 3)) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-medium text-neutral-500 dark:text-neutral-400">{{ $method->name }}</span>
                                    <span class="font-mono text-sm font-semibold text-neutral-900 dark:text-white">{{ $method->account_number }}</span>
                                    @if($method->account_holder)
                                        <span class="text-xs text-neutral-400 dark:text-neutral-500">{{ $method->account_holder }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="selection-indicator hidden">
                                    <i data-lucide="check-circle" class="size-5 text-orange-500"></i>
                                </div>
                                <button type="button" class="group rounded p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-800" title="Copy" onclick="event.stopPropagation(); copyNumber('{{ $method->account_number }}')">
                                    <i data-lucide="copy" class="size-4 text-neutral-400 transition group-hover:text-neutral-700 dark:text-neutral-500 dark:group-hover:text-neutral-300"></i>
                                </button>
                            </div>
                        </div>

                    {{-- QRIS Type --}}
                    @elseif($method->type === 'qris')
                        <div class="payment-method-item rounded-lg border-2 border-neutral-100 bg-neutral-50 p-4 cursor-pointer transition-all duration-200 hover:border-orange-300 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:border-orange-400"
                             onclick="selectPaymentMethod(this, 'QRIS', '{{ $method->nmid ?? 'QRIS Payment' }}', 'qris')"
                             data-method-name="QRIS"
                             data-method-number="{{ $method->nmid ?? 'QRIS Payment' }}"
                             data-method-type="qris">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-sm font-semibold text-neutral-900 dark:text-white">QRIS</span>
                                <div class="flex items-center gap-2">
                                    <div class="selection-indicator hidden">
                                        <i data-lucide="check-circle" class="size-5 text-orange-500"></i>
                                    </div>
                                    @if($method->nmid)
                                        <span class="text-xs text-neutral-500 dark:text-neutral-400">NMID: {{ $method->nmid }}</span>
                                    @endif
                                </div>
                            </div>

                            @if($method->qr_code_path && file_exists(public_path($method->qr_code_path)))
                                <div class="flex items-center justify-center rounded-lg bg-white p-3 dark:bg-neutral-900">
                                    <img src="{{ asset($method->qr_code_path) }}"
                                         alt="QRIS Code"
                                         class="h-auto w-full max-w-[200px] object-contain">
                                </div>
                                <p class="mt-2 text-center text-xs text-neutral-500 dark:text-neutral-400">
                                    Scan QRIS untuk pembayaran
                                </p>
                            @else
                                {{-- QRIS Placeholder --}}
                                <div class="flex items-center justify-center rounded-lg bg-white p-4 dark:bg-neutral-900">
                                    <div class="text-center">
                                        <div class="w-32 h-32 mx-auto mb-3 bg-neutral-200 dark:bg-neutral-700 rounded-lg flex items-center justify-center">
                                            <svg class="w-20 h-20 text-neutral-500 dark:text-neutral-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm10 0h2v2h-2v-2zm0 4h2v2h-2v-2zm4-4h2v2h-2v-2zm0 4h2v2h-2v-2zm-6-6h2v2h-2v-2zm2-2h2v2h-2v-2zm2 2h2v2h-2v-2z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">QRIS Code</p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-500 mt-1">Scan untuk pembayaran</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            @else
                {{-- Empty State when no payment methods --}}
                <div class="flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-neutral-300 bg-neutral-50 py-8 dark:border-neutral-700 dark:bg-neutral-900">
                    <i data-lucide="credit-card" class="size-10 text-neutral-300 dark:text-neutral-700 mb-2"></i>
                    <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400 text-center">
                        Metode pembayaran belum tersedia
                    </p>
                    <p class="mt-1 text-xs text-neutral-400 dark:text-neutral-600 text-center">
                        Silakan hubungi admin untuk informasi lebih lanjut
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- Submit Button (BOTTOM) --}}
    <button type="button" onclick="sendToWhatsapp()" class="w-full rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-neutral-900 shadow-md transition hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 dark:focus:ring-offset-black">
        Kirim
    </button>
</div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Global variable to store selected payment method
    let selectedPaymentMethod = null;

    // Function to select payment method
    function selectPaymentMethod(element, methodName, methodNumber, methodType) {
        // Check if this element is already selected
        const isCurrentlySelected = element.classList.contains('border-orange-400');
        
        // Remove selection from all payment methods
        document.querySelectorAll('.payment-method-item').forEach(item => {
            item.classList.remove('border-orange-400', 'bg-orange-50', 'dark:border-orange-500', 'dark:bg-orange-900/20');
            item.classList.add('border-neutral-100', 'dark:border-neutral-800');
            const indicator = item.querySelector('.selection-indicator');
            if (indicator) {
                indicator.classList.add('hidden');
            }
        });

        // If it was already selected, deselect it (toggle off)
        if (isCurrentlySelected) {
            selectedPaymentMethod = null;
        } else {
            // Add selection to clicked payment method (toggle on)
            element.classList.remove('border-neutral-100', 'dark:border-neutral-800');
            element.classList.add('border-orange-400', 'bg-orange-50', 'dark:border-orange-500', 'dark:bg-orange-900/20');
            
            const indicator = element.querySelector('.selection-indicator');
            if (indicator) {
                indicator.classList.remove('hidden');
            }

            // Store selected payment method
            selectedPaymentMethod = {
                name: methodName,
                number: methodNumber,
                type: methodType
            };
        }

        // Reinitialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Validate input and change border color
    function validateInput(input) {
        const icon = input.parentElement.querySelector('.input-icon');
        const value = input.value.trim();
        const MIN_NOMINAL = {{ $minimumNominal }};

        // Remove non-digit characters for nominal field validation
        let hasValue = value.length > 0;
        let isValid = true;

        if (input.id === 'nominal') {
            const cleanValue = value.replace(/\D/g, '');
            const numericValue = parseInt(cleanValue) || 0;
            hasValue = cleanValue.length > 0;
            isValid = hasValue && numericValue >= MIN_NOMINAL;

            // Show/hide nominal note
            const note = document.getElementById('nominal_note');
            if (hasValue && numericValue < MIN_NOMINAL) {
                note.classList.remove('hidden');
            } else {
                note.classList.add('hidden');
            }
        }

        if (hasValue && isValid) {
            // Has value and valid - green border with checkmark
            input.classList.remove('border-red-400');
            input.classList.add('border-green-400');
            icon.classList.remove('hidden');
        } else {
            // Empty or invalid - red border without checkmark
            input.classList.remove('border-green-400');
            input.classList.add('border-red-400');
            icon.classList.add('hidden');
        }

        // Reinitialize Lucide icons for the newly shown icon
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Initialize validation on page load
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = ['user_id', 'nickname', 'nominal'];
        inputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                validateInput(input);
            }
        });
    });

    // Format nominal to Indonesian Rupiah
    function formatNominal(input) {
        // Get current value and remove all non-digit characters
        let value = input.value.replace(/\D/g, '');

        // If empty, return
        if (value === '') {
            input.value = '';
            return;
        }

        // Format with thousand separators (dots in Indonesian format)
        let formatted = '';
        let count = 0;

        for (let i = value.length - 1; i >= 0; i--) {
            if (count > 0 && count % 3 === 0) {
                formatted = '.' + formatted;
            }
            formatted = value[i] + formatted;
            count++;
        }

        // Set the formatted value with "Rp " prefix
        input.value = 'Rp ' + formatted;
    }

    // Get raw nominal value (numbers only, without "Rp" and dots)
    function getRawNominal() {
        const nominalInput = document.getElementById('nominal');
        return nominalInput.value.replace(/\D/g, '');
    }

    function copyNumber(number) {
        navigator.clipboard.writeText(number).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Nomor ' + number + ' berhasil disalin!',
                confirmButtonColor: '#10b981',
                background: '#1f2937',
                color: '#fff',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }

    function previewFile(input) {
        const previewContainer = document.getElementById('preview_container');
        const previewImage = document.getElementById('preview_image');
        const uploadArea = document.getElementById('upload_area');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadArea.classList.add('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function removePreview() {
        const previewContainer = document.getElementById('preview_container');
        const uploadArea = document.getElementById('upload_area');
        const fileInput = document.getElementById('proof_file');

        // Clear file input
        fileInput.value = '';

        // Hide preview and show upload area
        previewContainer.classList.add('hidden');
        uploadArea.classList.remove('hidden');
    }

    async function uploadFile(file) {
        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch('/app/upload-proof', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) {
                console.error('Upload response not OK:', response.status, response.statusText);
                throw new Error('Upload failed');
            }

            const data = await response.json();
            console.log('Upload response:', data);
            return data.file_path;
        } catch (error) {
            console.error('Upload error:', error);
            return null;
        }
    }

    async function sendToWhatsapp() {
        const userId = document.getElementById('user_id').value;
        const nickname = document.getElementById('nickname').value;
        const nominalInput = document.getElementById('nominal');
        const nominal = nominalInput.value; // This will be "Rp 15.000"
        const nominalRaw = getRawNominal(); // This will be "15000"
        const nominalValue = parseInt(nominalRaw) || 0;
        const MIN_NOMINAL = {{ $minimumNominal }};
        const fileInput = document.getElementById('proof_file');

        if (!userId || !nickname || !nominal) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Mohon lengkapi semua data terlebih dahulu',
                confirmButtonColor: '#fbbf24',
                background: '#1f2937',
                color: '#fff'
            });
            return;
        }

        if (nominalValue < MIN_NOMINAL) {
            Swal.fire({
                icon: 'warning',
                title: 'Nominal Kurang',
                text: 'Minimal nominal adalah Rp {{ number_format($minimumNominal, 0, ',', '.') }}',
                confirmButtonColor: '#fbbf24',
                background: '#1f2937',
                color: '#fff'
            });
            return;
        }

        if (!selectedPaymentMethod) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Metode Pembayaran',
                text: 'Mohon pilih metode pembayaran terlebih dahulu',
                confirmButtonColor: '#fbbf24',
                background: '#1f2937',
                color: '#fff'
            });
            return;
        }

        @if(isset($service))
            const serviceName = "{{ $service->name }}";
            const serviceId = "{{ $service->id }}";
        @else
            const serviceName = "Aplikasi";
            const serviceId = "";
        @endif

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
            // Upload file if selected
            let filePath = null;
            if (fileInput.files && fileInput.files[0]) {
                console.log('Uploading file...', fileInput.files[0]);
                filePath = await uploadFile(fileInput.files[0]);
                console.log('File uploaded, path:', filePath);
            }

            // Submit order to backend
            const formData = new FormData();
            formData.append('service_id', serviceId);
            formData.append('service_name', serviceName);
            formData.append('user_id_input', userId);
            formData.append('nickname', nickname);
            formData.append('nominal', nominal);
            formData.append('payment_method', selectedPaymentMethod.name);
            formData.append('payment_number', selectedPaymentMethod.number);
            if (filePath) {
                formData.append('proof_image', filePath);
                console.log('Added proof_image to formData:', filePath);
            } else {
                console.log('No file uploaded or upload failed');
            }

            const response = await fetch('/app/submit-order', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Gagal mengirim pesanan');
            }

            const tgSent = data.tg_sent === true;
            const escapeHtml = (value) => String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
            const tgError = data.tg_error ? escapeHtml(data.tg_error) : '';

            // Show success message and redirect to status check page
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                html: tgSent
                    ? 'Pesanan berhasil dibuat!<br>Notifikasi telah dikirim ke Telegram admin.<br><br><strong>Mohon tunggu konfirmasi...</strong>'
                    : ('Pesanan berhasil dibuat, tapi Telegram gagal terkirim' + (tgError ? (':<br><small>' + tgError + '</small>') : '') + '.<br><br><strong>Mohon tunggu konfirmasi...</strong>'),
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK',
                background: '#1f2937',
                color: '#fff',
                iconColor: '#10b981',
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                // Redirect to transaction status page
                window.location.href = '/app/transaction-status/' + data.transaction_code;
            });

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Terjadi kesalahan saat memproses pesanan',
                confirmButtonColor: '#ef4444',
                background: '#1f2937',
                color: '#fff'
            });
        }
    }
</script>
