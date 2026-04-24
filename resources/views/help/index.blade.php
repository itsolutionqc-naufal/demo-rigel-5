<x-layouts::app :title="__('Pengaturan Bantuan WhatsApp')">
    <div class="container mx-auto px-4 py-6 space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Pengaturan Bantuan WhatsApp
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Atur nomor WhatsApp dan pesan template untuk kontak bantuan
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

        <!-- WhatsApp Settings Form -->
        <form method="POST" action="{{ route('help.update') }}" class="space-y-6">
            @csrf
            
            <!-- General WhatsApp Settings -->
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
                <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        Pengaturan WhatsApp Umum
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        Atur nomor WhatsApp dan pesan template umum yang digunakan untuk kontak bantuan
                    </p>
                </div>

                <div class="p-6 space-y-6">
                    <!-- WhatsApp Number -->
                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Nomor WhatsApp
                        </label>
                        <input
                            type="text"
                            id="whatsapp_number"
                            name="whatsapp_number"
                            value="{{ old('whatsapp_number', $whatsappNumber) }}"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500"
                            placeholder="Contoh: 6281234567890"
                            required
                        >
                        @error('whatsapp_number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            Format nomor harus dalam format internasional tanpa tanda '+', contoh: 6281234567890
                        </p>
                    </div>

                    <!-- WhatsApp Message Template -->
                    <div>
                        <label for="whatsapp_message_template" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Template Pesan WhatsApp Umum
                        </label>
                        <textarea
                            id="whatsapp_message_template"
                            name="whatsapp_message_template"
                            rows="4"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500"
                            placeholder="Masukkan template pesan WhatsApp umum..."
                            required
                        >{{ old('whatsapp_message_template', urldecode($whatsappMessageTemplate)) }}</textarea>
                        @error('whatsapp_message_template')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            Template pesan untuk bantuan umum
                        </p>
                    </div>

                    <!-- Preview Section -->
                    <div class="rounded-lg bg-neutral-50 dark:bg-neutral-700/50 p-4 border border-neutral-200 dark:border-neutral-600">
                        <h3 class="text-sm font-medium text-neutral-900 dark:text-white mb-2">
                            Pratinjau Tautan WhatsApp Umum
                        </h3>
                        <div class="bg-white dark:bg-neutral-800 p-3 rounded border border-neutral-200 dark:border-neutral-600">
                            <p class="text-sm text-neutral-600 dark:text-neutral-300 break-all">
                                https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappMessageTemplate }}
                            </p>
                        </div>
                        <div class="mt-3">
                            <a
                                href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappMessageTemplate }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white rounded-lg font-medium text-sm transition-colors"
                            >
                                <i data-lucide="external-link" class="size-4"></i>
                                <span>Tes Tautan WhatsApp Umum</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wallet Withdrawal Settings -->
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
                <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        Pengaturan Penarikan Wallet
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        Atur pesan template khusus untuk bantuan terkait penarikan wallet/komisi
                    </p>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Wallet WhatsApp Message Template -->
                    <div>
                        <label for="whatsapp_wallet_template" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Template Pesan Penarikan Wallet
                        </label>
                        <textarea
                            id="whatsapp_wallet_template"
                            name="whatsapp_wallet_template"
                            rows="3"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500"
                            placeholder="Contoh: Halo kak, mau nanya soal status pencairan saya"
                            required
                        >{{ old('whatsapp_wallet_template', urldecode($whatsappWalletTemplate)) }}</textarea>
                        @error('whatsapp_wallet_template')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            Template pesan khusus untuk pertanyaan tentang status penarikan wallet
                        </p>
                    </div>

                    <!-- Preview Section -->
                    <div class="rounded-lg bg-neutral-50 dark:bg-neutral-700/50 p-4 border border-neutral-200 dark:border-neutral-600">
                        <h3 class="text-sm font-medium text-neutral-900 dark:text-white mb-2">
                            Pratinjau Tautan WhatsApp Penarikan Wallet
                        </h3>
                        <div class="bg-white dark:bg-neutral-800 p-3 rounded border border-neutral-200 dark:border-neutral-600">
                            <p class="text-sm text-neutral-600 dark:text-neutral-300 break-all">
                                https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappWalletTemplate }}
                            </p>
                        </div>
                        <div class="mt-3">
                            <a
                                href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappWalletTemplate }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white rounded-lg font-medium text-sm transition-colors"
                            >
                                <i data-lucide="external-link" class="size-4"></i>
                                <span>Tes Tautan WhatsApp Wallet</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job History Settings -->
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
                <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        Pengaturan Job Riwayat
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        Atur pesan template khusus untuk bantuan terkait status transaksi/job
                    </p>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Job WhatsApp Message Template -->
                    <div>
                        <label for="whatsapp_job_template" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Template Pesan Job Riwayat
                        </label>
                        <textarea
                            id="whatsapp_job_template"
                            name="whatsapp_job_template"
                            rows="3"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500"
                            placeholder="Contoh: Halo kak, mau nanya soal status transaksi saya"
                            required
                        >{{ old('whatsapp_job_template', urldecode($whatsappJobTemplate)) }}</textarea>
                        @error('whatsapp_job_template')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            Template pesan khusus untuk pertanyaan tentang status transaksi/job
                        </p>
                    </div>

                    <!-- Preview Section -->
                    <div class="rounded-lg bg-neutral-50 dark:bg-neutral-700/50 p-4 border border-neutral-200 dark:border-neutral-600">
                        <h3 class="text-sm font-medium text-neutral-900 dark:text-white mb-2">
                            Pratinjau Tautan WhatsApp Job Riwayat
                        </h3>
                        <div class="bg-white dark:bg-neutral-800 p-3 rounded border border-neutral-200 dark:border-neutral-600">
                            <p class="text-sm text-neutral-600 dark:text-neutral-300 break-all">
                                https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappJobTemplate }}
                            </p>
                        </div>
                        <div class="mt-3">
                            <a
                                href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappJobTemplate }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white rounded-lg font-medium text-sm transition-colors"
                            >
                                <i data-lucide="external-link" class="size-4"></i>
                                <span>Tes Tautan WhatsApp Job</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm p-6">
                <div class="flex justify-end gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-neutral-900 hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200 rounded-lg font-medium text-sm text-white transition-colors"
                    >
                        <i data-lucide="save" class="size-4"></i>
                        <span>Simpan Semua Pengaturan</span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Information Section -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">
                Informasi Penting
            </h2>
            <div class="space-y-3 text-sm text-neutral-600 dark:text-neutral-400">
                <div class="flex items-start gap-2">
                    <i data-lucide="info" class="size-4 text-blue-500 mt-0.5 flex-shrink-0"></i>
                    <p>Setelah menyimpan pengaturan, semua tautan WhatsApp di sistem akan menggunakan nomor dan pesan terbaru.</p>
                </div>
                <div class="flex items-start gap-2">
                    <i data-lucide="info" class="size-4 text-blue-500 mt-0.5 flex-shrink-0"></i>
                    <p>Format nomor WhatsApp harus dalam format internasional (contoh: 6281234567890 untuk Indonesia).</p>
                </div>
                <div class="flex items-start gap-2">
                    <i data-lucide="info" class="size-4 text-blue-500 mt-0.5 flex-shrink-0"></i>
                    <p>Pesan template akan di-encode secara otomatis agar bisa digunakan dalam tautan WhatsApp.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        function initLucideIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Try to initialize immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLucideIcons);
        } else {
            initLucideIcons();
        }

        // Also try to initialize after a short delay to ensure all elements are rendered
        setTimeout(initLucideIcons, 100);
    </script>
</x-layouts::app>