<x-layouts::app :title="__('Detail Layanan')">
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 rounded-2xl shadow-lg">
                    <i data-lucide="package" class="size-8 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">
                        Detail Layanan
                    </h1>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        Informasi lengkap untuk layanan "{{ $service->name }}"
                    </p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($service->status === 'active')
                                bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200
                            @elseif($service->status === 'inactive')
                                bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200
                            @else
                                bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200
                            @endif">
                            {{ ucfirst($service->status) }}
                        </span>
                        <span class="text-xs text-neutral-400 dark:text-neutral-500">•</span>
                        <span class="text-xs text-neutral-500 dark:text-neutral-400">
                            Dibuat {{ $service->created_at->setTimezone('Asia/Jakarta')->format('d F Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('services.index') }}" 
                    class="inline-flex items-center gap-2 px-4 py-3 bg-neutral-100 dark:bg-neutral-800 hover:bg-neutral-200 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-300 border border-neutral-200 dark:border-neutral-600 rounded-xl font-medium text-sm transition-all duration-200 hover:scale-105"
                >
                    <i data-lucide="arrow-left" class="size-4"></i>
                    <span>Kembali</span>
                </a>
                <a 
                    href="{{ route('services.edit', $service) }}" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 dark:from-indigo-500 dark:to-indigo-600 dark:hover:from-indigo-600 dark:hover:to-indigo-700 text-white rounded-xl font-medium text-sm transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105"
                >
                    <i data-lucide="pencil" class="size-4"></i>
                    <span>Edit Layanan</span>
                </a>
            </div>
        </div>

        <!-- Service Details Card -->
        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-white to-neutral-50 dark:from-neutral-800 dark:to-neutral-900 shadow-xl overflow-hidden">
            <div class="p-8">
                <!-- Service Info Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                    <!-- Name -->
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                                <i data-lucide="package" class="size-4 text-indigo-600 dark:text-indigo-400"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Nama Layanan</h3>
                        </div>
                        <p class="text-xl font-bold text-neutral-900 dark:text-white">{{ $service->name }}</p>
                    </div>

                    <!-- Image -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                <i data-lucide="image" class="size-4 text-green-600 dark:text-green-400"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Gambar Layanan</h3>
                        </div>
                        <div class="relative">
                            @if($service->image)
                                <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="w-full max-w-sm h-64 rounded-2xl object-cover border-2 border-neutral-200 dark:border-neutral-700 shadow-lg">
                            @else
                                <div class="w-full max-w-sm h-64 rounded-2xl bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-700 dark:to-neutral-800 flex items-center justify-center border-2 border-dashed border-neutral-300 dark:border-neutral-600 shadow-lg">
                                    <div class="text-center">
                                        <i data-lucide="image" class="size-16 text-neutral-400 dark:text-neutral-600 mb-3"></i>
                                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Tidak ada gambar</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                                <i data-lucide="activity" class="size-4 text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Status Layanan</h3>
                        </div>
                        <span class="inline-flex items-center px-3 py-1.5 text-sm font-bold rounded-full
                            @if($service->status === 'active')
                                bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200
                            @elseif($service->status === 'inactive')
                                bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200
                            @else
                                bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200
                            @endif">
                            <span class="w-2 h-2 rounded-full mr-2
                                @if($service->status === 'active')
                                    bg-emerald-500
                                @elseif($service->status === 'inactive')
                                    bg-amber-500
                                @else
                                    bg-blue-500
                                @endif"></span>
                            {{ ucfirst($service->status) }}
                        </span>
                    </div>

                    <!-- Created At -->
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                <i data-lucide="calendar" class="size-4 text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Tanggal Dibuat</h3>
                        </div>
                        <p class="text-lg font-bold text-neutral-900 dark:text-white">{{ $service->created_at->setTimezone('Asia/Jakarta')->format('d F Y') }}</p>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $service->created_at->setTimezone('Asia/Jakarta')->format('H:i') }} WIB</p>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                    <!-- Commission -->
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                                <i data-lucide="percent" class="size-4 text-yellow-600 dark:text-yellow-400"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Komisi</h3>
                        </div>
                        <p class="text-lg font-bold text-neutral-900 dark:text-white">{{ $service->commission }}%</p>
                    </div>

                    <!-- WhatsApp Number -->
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                <i data-lucide="phone" class="size-4 text-green-600 dark:text-green-400"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Nomor WhatsApp</h3>
                        </div>
                        @if($service->whatsapp_number)
                            <a href="https://wa.me/{{ $service->whatsapp_number }}" target="_blank" class="inline-flex items-center gap-2 text-lg font-bold text-green-600 dark:text-green-400 hover:underline">
                                <i data-lucide="message-circle" class="size-5"></i>
                                +{{ $service->whatsapp_number }}
                            </a>
                        @else
                            <p class="text-lg font-medium text-neutral-500 dark:text-neutral-400">-</p>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-10 p-6 bg-gradient-to-r from-neutral-50 to-neutral-100 dark:from-neutral-900 dark:to-neutral-800 rounded-2xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <i data-lucide="info" class="size-4 text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-neutral-900 dark:text-white">Deskripsi Layanan</h3>
                    </div>
                    <div class="prose dark:prose-invert max-w-none text-neutral-700 dark:text-neutral-300 leading-relaxed">
                        {{ $service->description ?: 'Tidak ada deskripsi yang tersedia untuk layanan ini.' }}
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-neutral-200 dark:border-neutral-700">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                            <i data-lucide="alert-triangle" class="size-4 text-red-600 dark:text-red-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Tindakan Berbahaya</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Menghapus layanan tidak dapat dibatalkan</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        onclick="openDeleteModal()"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 dark:from-red-500 dark:to-red-600 dark:hover:from-red-600 dark:hover:to-red-700 text-white rounded-xl font-medium text-sm transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105"
                    >
                        <i data-lucide="trash" class="size-4"></i>
                        <span>Hapus Layanan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 hidden">
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-start gap-3 mb-4">
                <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                    <i data-lucide="alert-triangle" class="size-5 text-red-600 dark:text-red-400"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        Hapus Layanan?
                    </h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        Apakah Anda yakin ingin menghapus layanan "{{ $service->name }}"? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors"
                >
                    Batal
                </button>
                <form
                    method="POST"
                    action="{{ route('services.destroy', $service) }}"
                    class="inline"
                >
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white rounded-lg font-medium text-sm transition-colors"
                    >
                        Ya, Hapus
                    </button>
                </form>
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

        // Delete modal functions
        function openDeleteModal() {
            document.getElementById('delete-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</x-layouts::app>