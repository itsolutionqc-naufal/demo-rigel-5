<x-layouts::app.sidebar :title="'Edit Metode Pembayaran'">
    <flux:main>
        <div class="container mx-auto px-4 py-6">
            <div class="max-w-2xl mx-auto">
                <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="p-6 border-b border-neutral-200 dark:border-neutral-800">
                        <h1 class="text-xl font-bold text-neutral-900 dark:text-white">Edit Metode Pembayaran</h1>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('payment-methods.update', $paymentMethod) }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Nama Metode Pembayaran</label>
                                <input id="name" type="text" name="name" value="{{ old('name', $paymentMethod->name) }}" required autocomplete="name" autofocus
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">

                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Tipe Metode Pembayaran</label>
                                <select id="type" name="type" required
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                    <option value="">Pilih Tipe</option>
                                    <option value="bank_account" {{ old('type', $paymentMethod->type) === 'bank_account' ? 'selected' : '' }}>Rekening Bank</option>
                                    <option value="qris" {{ old('type', $paymentMethod->type) === 'qris' ? 'selected' : '' }}>QRIS</option>
                                </select>

                                @error('type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Account Number -->
                            <div class="bank-account-fields" style="{{ old('type', $paymentMethod->type) !== 'bank_account' ? 'display: none;' : '' }}">
                                <label for="account_number" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Nomor Rekening</label>
                                <input id="account_number" type="text" name="account_number" value="{{ old('account_number', $paymentMethod->account_number) }}" autocomplete="account_number"
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">

                                @error('account_number')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Account Holder -->
                            <div class="bank-account-fields" style="{{ old('type', $paymentMethod->type) !== 'bank_account' ? 'display: none;' : '' }}">
                                <label for="account_holder" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Nama Pemilik Rekening</label>
                                <input id="account_holder" type="text" name="account_holder" value="{{ old('account_holder', $paymentMethod->account_holder) }}" autocomplete="account_holder"
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500">

                                @error('account_holder')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- QR Code -->
                            <div class="qris-fields" style="{{ old('type', $paymentMethod->type) !== 'qris' ? 'display: none;' : '' }}">
                                <!-- NMID -->
                                <div class="mb-4">
                                    <label for="nmid" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">NMID (Nomor Merchant ID)</label>
                                    <input id="nmid" type="text" name="nmid" value="{{ old('nmid', $paymentMethod->nmid) }}" autocomplete="nmid"
                                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500"
                                        placeholder="ID1234567890123">

                                    @error('nmid')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- QR Code Image -->
                                <div>
                                    <label for="qr_code" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Gambar QRIS</label>
                                    <input id="qr_code" type="file" name="qr_code" accept="image/*"
                                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">

                                    @error('qr_code')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror

                                    @if($paymentMethod->qr_code_path)
                                        <div class="mt-3">
                                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">QRIS saat ini:</p>
                                            <img src="{{ asset($paymentMethod->qr_code_path) }}" alt="QRIS" class="w-32 h-32 rounded border border-neutral-200 dark:border-neutral-700">
                                        </div>
                                    @endif

                                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Format: JPEG, PNG. Ukuran maksimal: 2MB. Biarkan kosong jika tidak ingin mengganti.</p>
                                </div>
                            </div>

                            <!-- Is Active -->
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                <label for="is_active" class="ml-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Aktif</label>
                            </div>

                            <!-- Buttons -->
                            <div class="flex items-center justify-end gap-3 pt-4">
                                <a href="{{ route('admin.settings') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                                    <i data-lucide="x" class="size-4"></i>
                                    Batal
                                </a>
                                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
                                    <i data-lucide="save" class="size-4"></i>
                                    Update Metode Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </flux:main>
</x-layouts::app.sidebar>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const bankFields = document.querySelectorAll('.bank-account-fields');
    const qrisFields = document.querySelectorAll('.qris-fields');

    function toggleFields() {
        const selectedValue = typeSelect.value;

        bankFields.forEach(field => {
            field.style.display = selectedValue === 'bank_account' ? 'block' : 'none';
        });

        qrisFields.forEach(field => {
            field.style.display = selectedValue === 'qris' ? 'block' : 'none';
        });
    }

    typeSelect.addEventListener('change', toggleFields);

    // Initialize fields based on current selection
    toggleFields();

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
