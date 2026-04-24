<div>
    <div class="mb-6">
        <label for="payment_method" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
            Metode Pembayaran
        </label>
        <select
            id="payment_method"
            wire:model.live="selectedPaymentMethodId"
            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500"
        >
            <option value="">Pilih Metode Pembayaran</option>
            @foreach($paymentMethods as $method)
                <option value="{{ $method->id }}">{{ $method->name }}</option>
            @endforeach
        </select>
        <!-- Hidden input to submit the selected payment method ID -->
        <input type="hidden" name="payment_method_id" value="{{ $selectedPaymentMethodId }}">
    </div>

    <!-- QRIS Display Section -->
    @if($selectedPaymentMethod = $this->getSelectedPaymentMethodProperty())
        @if($selectedPaymentMethod->type === 'qris' && $selectedPaymentMethod->qr_code_path)
            <div class="mt-6 p-4 rounded-lg border border-emerald-200 bg-emerald-50 dark:border-emerald-800/30 dark:bg-emerald-900/20">
                <h3 class="text-lg font-medium text-emerald-800 dark:text-emerald-200 mb-3 flex items-center gap-2">
                    <i data-lucide="qr-code" class="size-5"></i>
                    Kode QRIS
                </h3>
                <div class="flex flex-col items-center">
                    <img
                        src="{{ asset($selectedPaymentMethod->qr_code_path) }}"
                        alt="QRIS Code"
                        class="w-48 h-48 rounded border border-neutral-200 dark:border-neutral-700 bg-white p-2"
                    >
                    <p class="mt-3 text-sm text-neutral-600 dark:text-neutral-400 text-center">
                        Silakan pindai kode QRIS ini menggunakan aplikasi pembayaran Anda
                    </p>
                </div>
            </div>
        @elseif($selectedPaymentMethod->type === 'bank_account')
            <div class="mt-6 p-4 rounded-lg border border-blue-200 bg-blue-50 dark:border-blue-800/30 dark:bg-blue-900/20">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-200 mb-3 flex items-center gap-2">
                    <i data-lucide="landmark" class="size-5"></i>
                    Informasi Rekening Bank
                </h3>
                <div class="space-y-2">
                    <p class="text-sm"><span class="font-medium">Nama Bank:</span> {{ $selectedPaymentMethod->name }}</p>
                    <p class="text-sm"><span class="font-medium">Nomor Rekening:</span> {{ $selectedPaymentMethod->account_number }}</p>
                    <p class="text-sm"><span class="font-medium">Atas Nama:</span> {{ $selectedPaymentMethod->account_holder }}</p>
                </div>
            </div>
        @endif
    @endif
</div>