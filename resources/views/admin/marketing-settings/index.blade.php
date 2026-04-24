<x-layouts::app :title="__('Pengaturan Marketing')">
    @php($fieldClass = 'w-full px-3 py-2 bg-white border border-neutral-300 rounded-lg text-sm text-neutral-900 placeholder:text-neutral-400 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:placeholder:text-neutral-500 focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition-colors')
    <div class="container mx-auto px-4 py-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Pengaturan Marketing
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Kelola konfigurasi khusus untuk role marketing.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 dark:border-emerald-900/40 bg-emerald-50 dark:bg-emerald-900/20 p-4 text-emerald-800 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    Bonus Marketing
                </h2>
                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                    Bonus yang didapat marketing saat membuat user baru.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.marketing-settings.update') }}" class="p-6 space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Mode Bonus
                        </label>
                        <select
                            name="bonus_mode"
                            id="bonus_mode"
                            class="{{ $fieldClass }}"
                        >
                            <option value="nominal" {{ old('bonus_mode', $bonusMode) === 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                            <option value="percent" {{ old('bonus_mode', $bonusMode) === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                            <option value="default" {{ old('bonus_mode', $bonusMode) === 'default' ? 'selected' : '' }}>Default (ikut config)</option>
                        </select>
                        @error('bonus_mode')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 p-4">
                        <p class="text-sm text-neutral-700 dark:text-neutral-300">
                            Bonus efektif saat ini:
                            <span class="font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($effectiveBonusAmount, 0, ',', '.') }}</span>
                        </p>
                        <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                            Basis default config: <span class="font-medium">Rp {{ number_format($defaultBaseAmount, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>

                <div id="bonusNominalWrap">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Nominal bonus buat user (Rp)
                    </label>
                    <input
                        type="number"
                        name="user_create_bonus_amount"
                        min="0"
                        step="1"
                        value="{{ old('user_create_bonus_amount', $bonusAmount) }}"
                        class="{{ $fieldClass }}"
                    />
                    @error('user_create_bonus_amount')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="bonusPercentWrap">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Persentase bonus (%)
                    </label>
                    <input
                        type="number"
                        name="user_create_bonus_percent"
                        min="0"
                        max="100"
                        step="0.01"
                        value="{{ old('user_create_bonus_percent', $bonusPercent) }}"
                        class="{{ $fieldClass }}"
                    />
                    <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                        Persen dihitung dari nilai default config: <span class="font-medium">Rp {{ number_format($defaultBaseAmount, 0, ',', '.') }}</span>.
                    </p>
                    @error('user_create_bonus_percent')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors"
                    >
                        <i data-lucide="save" class="size-4"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    Popup Download Aplikasi
                </h2>
                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                    Popup ala Facebook di halaman login.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.marketing-settings.update') }}" class="p-6 space-y-4">
                @csrf

                <div class="flex items-center justify-between gap-4 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 p-4">
                    <div>
                        <p class="text-sm font-medium text-neutral-900 dark:text-white">Aktifkan Popup</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Jika nonaktif, modal tidak akan muncul di `/login`.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="download_prompt_enabled" value="0" />
                        <label class="relative inline-flex cursor-pointer items-center">
                        <input
                            type="checkbox"
                            name="download_prompt_enabled"
                            value="1"
                            {{ old('download_prompt_enabled', $downloadPromptEnabled ? '1' : '0') === '1' ? 'checked' : '' }}
                            class="peer sr-only"
                        />
                        <span class="h-6 w-11 rounded-full bg-neutral-200 transition-colors peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 dark:bg-neutral-700 peer-checked:bg-indigo-600 dark:peer-checked:bg-indigo-500"></span>
                        <span class="pointer-events-none absolute left-1 top-1 h-4 w-4 rounded-full bg-white shadow-sm transition-transform peer-checked:translate-x-5"></span>
                        </label>
                        <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            {{ old('download_prompt_enabled', $downloadPromptEnabled ? '1' : '0') === '1' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Link Download Aplikasi
                        </label>
                        <input
                            type="url"
                            name="download_url"
                            value="{{ old('download_url', $downloadUrl) }}"
                            placeholder="https://..."
                            class="{{ $fieldClass }}"
                        />
                        <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                            Jika kosong, tombol “Download aplikasi” akan nonaktif.
                        </p>
                        @error('download_url')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 p-4">
                        <p class="text-sm font-medium text-neutral-900 dark:text-white">Preview singkat</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Teks mengikuti field di bawah.</p>
                        <div class="mt-3 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-3">
                            <p class="text-sm font-semibold text-neutral-900 dark:text-white">
                                {{ $downloadPromptTitle }}
                            </p>
                            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                {{ $downloadPromptBody }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Judul
                        </label>
                        <input
                            type="text"
                            name="download_prompt_title"
                            value="{{ old('download_prompt_title', $downloadPromptTitle) }}"
                            class="{{ $fieldClass }}"
                        />
                        @error('download_prompt_title')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Deskripsi
                        </label>
                        <textarea
                            name="download_prompt_body"
                            rows="3"
                            class="{{ $fieldClass }}"
                        >{{ old('download_prompt_body', $downloadPromptBody) }}</textarea>
                        @error('download_prompt_body')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors"
                    >
                        <i data-lucide="save" class="size-4"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function initLucideIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLucideIcons);
        } else {
            initLucideIcons();
        }

        setTimeout(initLucideIcons, 100);

        (function () {
            const mode = document.getElementById('bonus_mode');
            const nominal = document.getElementById('bonusNominalWrap');
            const percent = document.getElementById('bonusPercentWrap');

            function sync() {
                const value = mode ? mode.value : 'nominal';
                if (nominal) nominal.classList.toggle('hidden', value !== 'nominal');
                if (percent) percent.classList.toggle('hidden', value !== 'percent');
            }

            if (mode) {
                mode.addEventListener('change', sync);
            }

            sync();
        })();
    </script>
</x-layouts::app>
