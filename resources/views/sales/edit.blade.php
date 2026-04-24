<x-layouts::app :title="__('Edit Transaksi')">
    @php
        $routePrefix = auth()->user()->isMarketing() ? 'marketing.' : '';
    @endphp
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm p-6">
                <h2 class="text-xl font-bold text-neutral-900 dark:text-white mb-6">
                    {{ __('Edit Transaksi') }}
                </h2>

                <form method="POST" action="{{ route($routePrefix.'sales.update', $saleTransaction) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="customer_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Nama Pelanggan
                        </label>
                        <input
                            id="customer_name"
                            type="text"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 @error('customer_name') border-red-500 @enderror"
                            name="customer_name"
                            value="{{ old('customer_name', $saleTransaction->customer_name) }}"
                            required
                            autocomplete="customer_name"
                            autofocus
                        >

                        @error('customer_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="customer_phone" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Nomor Telepon Pelanggan
                        </label>
                        <input
                            id="customer_phone"
                            type="tel"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 @error('customer_phone') border-red-500 @enderror"
                            name="customer_phone"
                            value="{{ old('customer_phone', $saleTransaction->customer_phone) }}"
                            autocomplete="customer_phone"
                        >

                        @error('customer_phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Jumlah
                        </label>
                        <input
                            id="amount"
                            type="number"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 @error('amount') border-red-500 @enderror"
                            name="amount"
                            value="{{ old('amount', $saleTransaction->amount) }}"
                            required
                            autocomplete="amount"
                        >

                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="commission_rate" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Persentase Komisi (%)
                            </label>
                            <input
                                id="commission_rate"
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 @error('commission_rate') border-red-500 @enderror"
                                name="commission_rate"
                                value="{{ old('commission_rate', $saleTransaction->commission_rate) }}"
                                required
                                autocomplete="commission_rate"
                            >

                            @error('commission_rate')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Jumlah Komisi
                            </label>
                            <div class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                <span id="commission_amount_display">Rp {{ number_format($saleTransaction->commission_amount ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Status
                        </label>
                        <select
                            id="status"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 @error('status') border-red-500 @enderror"
                            name="status"
                        >
                            <option value="process" {{ old('status', $saleTransaction->status) === 'process' ? 'selected' : '' }}>
                                Sedang Proses
                            </option>
                            <option value="success" {{ old('status', $saleTransaction->status) === 'success' ? 'selected' : '' }}>
                                Sukses
                            </option>
                            <option value="failed" {{ old('status', $saleTransaction->status) === 'failed' ? 'selected' : '' }}>
                                Gagal
                            </option>
                        </select>

                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    @livewire('payment-method-selector', ['defaultValue' => old('payment_method_id', $saleTransaction->payment_method_id)])

                    <div class="flex justify-end gap-3">
                        <a
                            href="{{ route($routePrefix.'sales.index') }}"
                            class="inline-flex items-center rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                        >
                            {{ __('Batal') }}
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200"
                        >
                            {{ __('Simpan Perubahan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Update commission amount when amount or commission rate changes
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const commissionRateInput = document.getElementById('commission_rate');
            const commissionDisplay = document.getElementById('commission_amount_display');

            function updateCommissionDisplay() {
                const amount = parseFloat(amountInput.value) || 0;
                const rate = parseFloat(commissionRateInput.value) || 0;
                const commission = amount * (rate / 100);

                // Format as currency
                const formatter = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                });

                commissionDisplay.textContent = formatter.format(commission);
            }

            // Add event listeners
            amountInput.addEventListener('input', updateCommissionDisplay);
            commissionRateInput.addEventListener('input', updateCommissionDisplay);

            // Initialize the display
            updateCommissionDisplay();
        });
    </script>
</x-layouts::app>
