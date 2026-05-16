<x-layouts::app :title="__('Tambah Pesanan Baru')">
    @php
        $routePrefix = auth()->user()->isMarketing() ? 'marketing.' : '';
    @endphp
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm p-6">
                <h2 class="text-xl font-bold text-neutral-900 dark:text-white mb-6">
                    {{ __('Tambah Pesanan Baru') }}
                </h2>

                <form method="POST" action="{{ route($routePrefix.'sales.store') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="customer_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Nama Pelanggan
                        </label>
                        <input
                            id="customer_name"
                            type="text"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 @error('customer_name') border-red-500 @enderror"
                            name="customer_name"
                            value="{{ old('customer_name') }}"
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
                            value="{{ old('customer_phone') }}"
                            autocomplete="customer_phone"
                        >

                        @error('customer_phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Nominal Pesanan
                        </label>
                        <input
                            id="amount"
                            type="number"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 @error('amount') border-red-500 @enderror"
                            name="amount"
                            value="{{ old('amount') }}"
                            required
                            autocomplete="amount"
                        >

                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
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
                            value="{{ old('commission_rate', 1.00) }}"
                            required
                            autocomplete="commission_rate"
                        >

                        @error('commission_rate')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    @livewire('payment-method-selector')

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
                            {{ __('Simpan Pesanan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
