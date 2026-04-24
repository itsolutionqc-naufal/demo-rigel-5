<x-layouts::app :title="__('Edit Layanan')">
    <div class="container mx-auto px-4 py-6">
        @php
            $isTalentHunter = ($service->category ?? null) === 'talent_hunter';
            $selectedCategory = old('category', $service->category);
            $selectedStatus = old('status', $service->status);
        @endphp

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Edit Layanan
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Memperbarui informasi untuk "{{ $service->name }}"
                </p>
            </div>

            <a
                href="{{ route('services.index', $isTalentHunter ? ['category' => 'talent_hunter'] : []) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors"
            >
                <i data-lucide="arrow-left" class="size-4"></i>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Edit Form -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
            <form method="POST" action="{{ route('services.update', $service) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Form Header -->
                <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        Informasi Layanan
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        Lengkapi formulir di bawah untuk memperbarui layanan
                    </p>
                </div>

                <!-- Form Body -->
                <div class="p-6 space-y-6">
                    <div>
                        <flux:select name="category" label="Kategori Layanan">
                            <option value="" @selected(blank($selectedCategory))>-- Pilih Kategori --</option>
                            <option value="reseller_coin" @selected($selectedCategory === 'reseller_coin')>Reseller Coin</option>
                            <option value="talent_hunter" @selected($selectedCategory === 'talent_hunter')>Talent Hunter</option>
                        </flux:select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <flux:input
                            name="name"
                            label="Nama Aplikasi"
                            placeholder="Masukkan nama aplikasi"
                            :value="old('name', $service->name)"
                            required
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Gambar Layanan
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-300 dark:border-neutral-600 border-dashed rounded-lg hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors">
                            <div class="space-y-2 text-center">
                                @if($service->image)
                                    <img id="current-image" src="{{ asset($service->image) }}" alt="Current image" class="mx-auto h-32 rounded-lg object-cover">
                                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Gambar saat ini</p>
                                @else
                                    <i data-lucide="image-plus" class="mx-auto h-12 w-12 text-neutral-400"></i>
                                @endif
                                <div class="flex text-sm text-neutral-600 dark:text-neutral-400">
                                    <label for="image-upload" class="relative cursor-pointer bg-white dark:bg-neutral-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 focus-within:outline-none">
                                        <span>Pilih file baru</span>
                                        <input id="image-upload" type="file" class="sr-only" name="image" accept="image/*">
                                    </label>
                                    <p class="pl-1">atau drag & drop</p>
                                </div>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    PNG, JPG, GIF, WEBP hingga 5MB
                                </p>
                            </div>
                        </div>
                        <!-- Filename Display -->
                        <div id="filename-display" class="mt-2 text-sm text-neutral-600 dark:text-neutral-400 {{ $service->image ? 'hidden' : '' }}">
                            <span id="filename-text"></span>
                        </div>
                        <!-- Image Preview -->
                        <div id="image-preview-container" class="mt-4 {{ $service->image ? 'hidden' : '' }}">
                            <img id="image-preview" src="" alt="Preview" class="h-48 rounded-lg object-contain border border-neutral-300 dark:border-neutral-600">
                            <button
                                type="button"
                                onclick="removeImage()"
                                class="mt-2 text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300"
                            >
                                Hapus gambar
                            </button>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Commission Rate -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Persentase Komisi (%)
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                name="commission_rate"
                                value="{{ old('commission_rate', $service->commission_rate ?? 0) }}"
                                step="0.01"
                                min="0"
                                max="100"
                                placeholder="Contoh: 3.00"
                                class="w-full rounded-lg border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 px-3 py-2 text-neutral-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-neutral-500 dark:text-neutral-400">%</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            Komisi yang didapatkan user dari setiap transaksi (contoh: 3 untuk 3%)
                        </p>
                        @error('commission_rate')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WhatsApp Number -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                            Nomor WhatsApp Admin
                        </label>
                        <div class="flex rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-neutral-300 bg-neutral-50 dark:bg-neutral-700 dark:border-neutral-600 min-w-[3.5rem]">
                                <span class="text-sm font-semibold text-neutral-600 dark:text-neutral-300">+62</span>
                            </span>
                            <input
                                type="text"
                                name="whatsapp_number"
                                placeholder="81234567890"
                                value="{{ old('whatsapp_number', $service->whatsapp_number) ? (str_starts_with(old('whatsapp_number', $service->whatsapp_number), '62') ? substr(old('whatsapp_number', $service->whatsapp_number), 2) : old('whatsapp_number', $service->whatsapp_number)) : '8' }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '8')"
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-r-lg border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white text-sm focus:ring-2 focus:ring-neutral-900 focus:border-neutral-900 dark:focus:ring-neutral-500 dark:focus:border-neutral-500"
                            />
                        </div>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            Format: +62 8xxxxxxxxxx (contoh: +62 81234567890)
                        </p>
                        @error('whatsapp_number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telegram Bot -->
                    <div>
	                        <flux:select name="telegram_bot_id" label="Bot Telegram" :value="old('telegram_bot_id', $service->telegram_bot_id)">
	                            <option value="">-- Gunakan Bot Default --</option>
	                            @foreach(\App\Models\TelegramBot::active()->get() as $bot)
	                                <option value="{{ $bot->id }}" {{ old('telegram_bot_id', $service->telegram_bot_id) == $bot->id ? 'selected' : '' }}>
	                                    {{ $bot->name }} ({{ '@' . $bot->username }})
	                                </option>
	                            @endforeach
	                        </flux:select>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            Pilih bot yang akan mengirim notifikasi untuk layanan ini
                        </p>
                        @error('telegram_bot_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telegram Chat ID (Override) -->
                    <div>
                        <flux:input
                            name="telegram_chat_id"
                            label="Telegram Chat ID (Opsional)"
                            placeholder="123456789"
                            :value="old('telegram_chat_id', $service->telegram_chat_id)"
                            type="text"
                            description="Kosongkan jika ingin pakai Chat ID dari bot yang dipilih"
                        />
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            Cara mendapatkan Chat ID: Chat bot @userinfobot di Telegram
                        </p>
                        @error('telegram_chat_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <flux:select name="status" label="Status Layanan">
                            <option value="draft" @selected($selectedStatus === 'draft')>Draft</option>
                            <option value="active" @selected($selectedStatus === 'active')>Aktif</option>
                            <option value="inactive" @selected($selectedStatus === 'inactive')>Tidak Aktif</option>
                        </flux:select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900/30">
                        <div>
                            <p class="text-sm font-semibold text-neutral-900 dark:text-white">Aktifkan di Mobile</p>
                            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Jika nonaktif, tidak muncul di pilihan layanan Hunter.</p>
                        </div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" class="size-4 accent-indigo-600" {{ old('is_active', $service->is_active ? '1' : '') ? 'checked' : '' }}>
                            <span class="text-sm text-neutral-700 dark:text-neutral-300">Aktif</span>
                        </label>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="p-6 border-t border-neutral-200 dark:border-neutral-700">
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 sm:space-x-reverse gap-3">
                        <a
                            href="{{ route('services.index', $isTalentHunter ? ['category' => 'talent_hunter'] : []) }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors"
                        >
                            <i data-lucide="x" class="size-4"></i>
                            <span>Batal</span>
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors"
                        >
                            <i data-lucide="save" class="size-4"></i>
                            <span>Perbarui Layanan</span>
                        </button>
                    </div>
                </div>
            </form>
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

        // Handle image preview and filename display
        document.getElementById('image-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Display filename
                document.getElementById('filename-text').textContent = file.name;
                document.getElementById('filename-display').classList.remove('hidden');

                // Display image preview
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('image-preview').src = event.target.result;
                    document.getElementById('image-preview-container').classList.remove('hidden');
                    // Hide the current image when preview is shown
                    const currentImage = document.getElementById('current-image');
                    if(currentImage) {
                        currentImage.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(file);
            }
        });

        // Remove image function
        function removeImage() {
            document.getElementById('image-upload').value = '';
            document.getElementById('filename-display').classList.add('hidden');
            document.getElementById('filename-text').textContent = '';
            document.getElementById('image-preview-container').classList.add('hidden');
            document.getElementById('image-preview').src = '';
            // Show the current image again if it exists
            const currentImage = document.getElementById('current-image');
            if(currentImage) {
                currentImage.classList.remove('hidden');
            }
        }
    </script>
</x-layouts::app>
