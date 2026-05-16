<x-layouts::app :title="__('Buat Artikel')">
    <div x-data="{
        showModal: false,
        toastMessage: '',
        toastType: 'success',
        showToast(message, type) {
            this.toastMessage = message;
            this.toastType = type;
            this.showModal = true;
            setTimeout(() => {
                this.showModal = false;
                if (type === 'success') {
                    window.location.href = '{{ route('articles') }}';
                }
            }, 3000);
        }
    }" class="container mx-auto px-4 py-6 space-y-6">
        <!-- Toast Notification -->
        <template x-teleport="body">
            <div
                x-show="showModal"
                x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transform transition ease-in duration-200"
                x-transition:leave-start="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave-end="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                class="fixed bottom-4 right-4 z-50"
                style="display: none;"
            >
                <div class="flex items-center gap-3 px-4 py-3 bg-neutral-800 dark:bg-neutral-700 rounded-lg shadow-lg min-w-[300px]">
                    <div class="flex-shrink-0">
                        <template x-if="toastType === 'success'">
                            <i data-lucide="check" class="size-5 text-white"></i>
                        </template>
                        <template x-if="toastType === 'error'">
                            <i data-lucide="x" class="size-5 text-red-400"></i>
                        </template>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white" x-text="toastMessage"></p>
                    </div>
                    <button
                        @click="showModal = false"
                        class="flex-shrink-0 text-neutral-400 hover:text-white transition-colors"
                    >
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
            </div>
        </template>

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Buat Artikel Baru
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Isi form di bawah untuk membuat artikel baru
                </p>
            </div>

            <a href="{{ route('articles') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors">
                <i data-lucide="arrow-left" class="size-4"></i>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Form -->
        <livewire:article-form />
    </div>
</x-layouts::app>
