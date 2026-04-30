<div>
    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <style>
        /* Dark Mode Styles for Quill Editor in Modal */
        .ql-toolbar.ql-snow {
            border-color: #4b5563 !important;
            background-color: #1f2937 !important;
        }

        .ql-toolbar.ql-snow button {
            color: #d1d5db !important;
        }

        .ql-toolbar.ql-snow button:hover,
        .ql-toolbar.ql-snow button.ql-active {
            color: #6366f1 !important;
            background-color: #374151 !important;
        }

        .ql-toolbar.ql-snow .ql-picker-label {
            color: #d1d5db !important;
        }

        .ql-toolbar.ql-snow .ql-picker-label:hover {
            color: #6366f1 !important;
        }

        .ql-toolbar.ql-snow .ql-picker-options {
            background-color: #1f2937 !important;
            border-color: #4b5563 !important;
        }

        .ql-toolbar.ql-snow .ql-picker-item {
            color: #d1d5db !important;
        }

        .ql-toolbar.ql-snow .ql-picker-item:hover,
        .ql-toolbar.ql-snow .ql-picker-item.ql-selected {
            color: #6366f1 !important;
            background-color: #374151 !important;
        }

        .ql-toolbar.ql-snow .ql-stroke {
            stroke: #9ca3af !important;
        }

        .ql-toolbar.ql-snow button:hover .ql-stroke,
        .ql-toolbar.ql-snow button.ql-active .ql-stroke {
            stroke: #6366f1 !important;
        }

        .ql-container.ql-snow {
            border-color: #4b5563 !important;
            background-color: #111827 !important;
            color: #f3f4f6 !important;
        }

        .ql-editor.ql-blank::before {
            color: #6b7280 !important;
        }

        .ql-editor {
            color: #f3f4f6 !important;
            background-color: #111827 !important;
        }

        .ql-editor a {
            color: #818cf8 !important;
        }

        .ql-editor blockquote {
            border-left-color: #4b5563 !important;
            color: #d1d5db !important;
        }

        .ql-editor pre {
            background-color: #1f2937 !important;
            border-color: #4b5563 !important;
        }

        .ql-editor code {
            background-color: #1f2937 !important;
            color: #fbbf24 !important;
        }

        .ql-editor img {
            max-width: 100% !important;
            height: auto !important;
        }
    </style>

    <div class="space-y-6">
        <!-- Flash Message -->
        @if(session()->has('success'))
            <div class="p-4 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800">
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session()->get('success') }}</p>
            </div>
        @endif

        <!-- Filters Section -->
        <div class="p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="filter" class="size-5 text-neutral-400"></i>
                    <span class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Filter & Pencarian</span>
                </div>
                @if($search || $category || $status || $start_date || $end_date)
                    <button
                        wire:click="resetFilters"
                        type="button"
                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white transition-colors"
                    >
                        <i data-lucide="x" class="size-3"></i>
                        Reset Filter
                    </button>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Search -->
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-neutral-400"></i>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Cari artikel..."
                        class="w-full pl-10 pr-4 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    />
                </div>

                <!-- Category Filter (Dynamic) -->
                <select
                    wire:model.live="category"
                    class="w-full px-4 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>

                <!-- Status Filter -->
                <select
                    wire:model.live="status"
                    class="w-full px-4 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>

                <!-- Start Date Filter -->
                <input
                    type="date"
                    wire:model.live="start_date"
                    class="w-full px-4 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />

                <!-- End Date Filter -->
                <input
                    type="date"
                    wire:model.live="end_date"
                    class="w-full px-4 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
            </div>
        </div>

        <!-- Articles List -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
            <!-- Selected Items Info with Delete Dropdown -->
            @if(count($selectedItems) > 0)
                <div class="px-6 py-3 bg-indigo-50 dark:bg-indigo-900/20 border-b border-indigo-200 dark:border-indigo-800 flex items-center justify-between">
                    <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300">
                        {{ count($selectedItems) }} artikel dipilih
                    </span>
                    <div class="flex items-center gap-2">
                        <flux:button variant="ghost" size="sm" wire:click="deselectAll">
                            Batal Pilih
                        </flux:button>
                        
                        <flux:dropdown position="top" align="end">
                            <flux:button variant="danger" size="sm" icon="trash">
                                Hapus
                            </flux:button>
                            <flux:menu>
                                <flux:menu.item wire:click="confirmDeleteSelected" icon="trash">
                                    Hapus Terpilih ({{ count($selectedItems) }})
                                </flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item wire:click="confirmDeleteAll" icon="trash" class="text-red-600">
                                    Hapus Semua Artikel
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                </div>
            @else
                <!-- Delete All Button when nothing selected -->
                <div class="px-6 py-3 bg-neutral-50 dark:bg-neutral-900/50 border-b border-neutral-200 dark:border-neutral-700 flex items-center justify-between">
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">
                        Total: <strong>{{ \App\Models\Article::count() }}</strong> artikel
                    </span>
                    @if(\App\Models\Article::count() > 0)
                        <flux:button variant="danger" size="sm" icon="trash" wire:click="confirmDeleteAll">
                            Hapus Semua Artikel
                        </flux:button>
                    @endif
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
	                        <tr>
	                            <th class="px-4 py-3 text-center w-12">
	                                <label for="articles-select-all" class="sr-only">Pilih semua artikel</label>
	                                <input
	                                    type="checkbox"
	                                    id="articles-select-all"
	                                    name="articles_select_all"
	                                    wire:model.live="selectAll"
	                                    aria-label="Pilih semua artikel"
	                                    class="w-4 h-4 text-indigo-600 border-neutral-300 rounded focus:ring-indigo-500 cursor-pointer"
	                                />
	                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider w-16">
                                No
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider w-24">
                                Gambar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Judul
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Views
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
	                        @forelse($articles as $index => $article)
	                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
	                                <td class="px-4 py-4 text-center">
	                                    <label for="article-select-{{ $article->id }}" class="sr-only">Pilih artikel {{ $article->title }}</label>
	                                    <input
	                                        type="checkbox"
	                                        id="article-select-{{ $article->id }}"
	                                        name="selected_items[]"
	                                        wire:model.live="selectedItems"
	                                        value="{{ $article->id }}"
	                                        aria-label="Pilih artikel {{ $article->title }}"
	                                        class="w-4 h-4 text-indigo-600 border-neutral-300 rounded focus:ring-indigo-500 cursor-pointer"
	                                    />
	                                </td>
                                <td class="px-4 py-4 text-center text-sm text-neutral-600 dark:text-neutral-400">
                                    {{ ($articles->currentPage() - 1) * $articles->perPage() + $index + 1 }}
                                </td>
                                <td class="px-4 py-4">
                                    @if($article->image)
                                        <img src="{{ asset($article->image) }}" 
                                             alt="{{ $article->title }}" 
                                             loading="lazy"
                                             class="w-16 h-16 object-cover mx-auto border border-neutral-200 dark:border-neutral-700">
                                    @else
                                        <div class="w-16 h-16 bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center mx-auto border border-neutral-200 dark:border-neutral-700">
                                            <i data-lucide="image" class="size-6 text-neutral-400 dark:text-neutral-600"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-neutral-900 dark:text-white line-clamp-1">
                                            {{ Str::limit($article->title, 50) }}
                                        </p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                            {{ Str::limit($article->excerpt, 80) }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                                        {{ $article->category ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($article->status === 'published')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200">
                                            <i data-lucide="check-circle" class="size-3"></i>
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200">
                                            <i data-lucide="file" class="size-3"></i>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-600 dark:text-neutral-400">
                                    {{ $article->views }}
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-600 dark:text-neutral-400">
                                    {{ $article->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('articles.show', $article) }}" class="inline-flex items-center justify-center p-2 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 rounded-lg transition-colors">
                                            <i data-lucide="eye" class="size-4"></i>
                                        </a>
                                        <a href="{{ route('articles.edit', $article) }}" class="inline-flex items-center justify-center p-2 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 rounded-lg transition-colors">
                                            <i data-lucide="pencil" class="size-4"></i>
                                        </a>
                                        <button
                                            type="button"
                                            onclick="window.confirmDelete({{ $article->id }})"
                                            class="inline-flex items-center justify-center p-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-800/50 text-red-600 dark:text-red-400 rounded-lg transition-colors"
                                        >
                                            <i data-lucide="trash" class="size-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="file-x" class="size-16 text-neutral-300 dark:text-neutral-700 mb-4"></i>
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                            Belum ada artikel
                                        </p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                            Mulai dengan membuat artikel baru
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($articles->hasPages())
                <div class="flex items-center justify-between gap-2 px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
                    {{-- Previous Button --}}
                    @if($articles->onFirstPage())
                        <span class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-neutral-400 dark:text-neutral-600 cursor-not-allowed">
                            <i data-lucide="chevron-left" class="size-3"></i>
                            <span>Prev</span>
                        </span>
                    @else
                        <button wire:click="previousPage" wire:loading.attr="disabled" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                            <i data-lucide="chevron-left" class="size-3"></i>
                            <span>Prev</span>
                        </button>
                    @endif

                    {{-- Page Info --}}
                    <span class="text-xs text-neutral-600 dark:text-neutral-400 font-medium">
                        {{ $articles->currentPage() }} / {{ $articles->lastPage() }}
                    </span>

                    {{-- Next Button --}}
                    @if($articles->hasMorePages())
                        <button wire:click="nextPage" wire:loading.attr="disabled" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                            <span>Next</span>
                            <i data-lucide="chevron-right" class="size-3"></i>
                        </button>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-neutral-400 dark:text-neutral-600 cursor-not-allowed">
                            <span>Next</span>
                            <i data-lucide="chevron-right" class="size-3"></i>
                        </span>
                    @endif
                </div>

                {{-- Result Info --}}
                <div class="px-6 py-3 border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900">
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 text-center">
                        Showing {{ $articles->firstItem() }} to {{ $articles->lastItem() }} of {{ $articles->total() }} results
                    </p>
                </div>
            @endif
        </div>

        <!-- Form Edit Modal (Only for edit mode) -->
        @if($showModal && $isEditMode)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-6 border-b border-neutral-200 dark:border-neutral-700">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                            Edit Artikel
                        </h3>
                        <button wire:click="closeModal" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                            <i data-lucide="x" class="size-5"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-4">
                        <flux:input
                            wire:model.live="title"
                            label="Judul Artikel"
                            placeholder="Masukkan judul artikel"
                            required
                        />

                        <flux:input
                            wire:model="article_category"
                            label="Kategori"
                            placeholder="Contoh: Teknologi, Bisnis, dll"
                        />

                        <flux:textarea
                            wire:model="excerpt"
                            label="Excerpt (Ringkasan Singkat)"
                            placeholder="Ringkasan singkat artikel..."
                            rows="2"
                        />

                        <!-- Quill Editor for Content -->
                        <div wire:ignore>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Konten Artikel
                            </label>
                            <input type="hidden" wire:model="content">

                            <div id="quill-edit-container" class="rounded-lg overflow-hidden border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-900">
                                <div id="quill-edit-toolbar" class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800">
                                    <span class="ql-formats">
                                        <select class="ql-header">
                                            <option value="1">Heading 1</option>
                                            <option value="2">Heading 2</option>
                                            <option value="3">Heading 3</option>
                                            <option value="0">Normal</option>
                                        </select>
                                    </span>
                                    <span class="ql-formats">
                                        <button class="ql-bold"></button>
                                        <button class="ql-italic"></button>
                                        <button class="ql-underline"></button>
                                        <button class="ql-strike"></button>
                                    </span>
                                    <span class="ql-formats">
                                        <select class="ql-color"></select>
                                        <select class="ql-background"></select>
                                    </span>
                                    <span class="ql-formats">
                                        <button class="ql-list" value="ordered"></button>
                                        <button class="ql-list" value="bullet"></button>
                                        <select class="ql-align">
                                            <option selected></option>
                                            <option value="center"></option>
                                            <option value="right"></option>
                                            <option value="justify"></option>
                                        </select>
                                    </span>
                                    <span class="ql-formats">
                                        <button class="ql-link"></button>
                                        <button class="ql-image"></button>
                                        <button class="ql-code-block"></button>
                                    </span>
                                    <span class="ql-formats">
                                        <button class="ql-clean"></button>
                                    </span>
                                </div>
                                <div id="quill-edit-editor" style="min-height: 300px;"></div>
                            </div>
                        </div>

                        <flux:select wire:model="article_status" label="Status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </flux:select>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end gap-2 p-6 border-t border-neutral-200 dark:border-neutral-700">
                        <flux:button variant="primary" wire:click="update">
                            Simpan Perubahan
                        </flux:button>
                        <flux:button variant="danger" wire:click="closeModal">
                            Batal
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if($confirmDelete)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-xl max-w-md w-full p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                            <i data-lucide="alert-triangle" class="size-5 text-red-600 dark:text-red-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                            Hapus Artikel?
                        </h3>
                    </div>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-6">
                        Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex items-center justify-end gap-2">
                        <flux:button variant="ghost" wire:click="cancelDelete">
                            Batal
                        </flux:button>
                        <flux:button variant="danger" wire:click="delete">
                            Ya, Hapus
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Quill Editor JS -->
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
        lucide.createIcons();

        (function() {
            'use strict';
            
            // Store Quill instance globally
            let quillEdit = null;
            let initAttempts = 0;
            const MAX_INIT_ATTEMPTS = 50;
            let livewireComponentEdit = null;
            let componentIdEdit = null;

            // Get Livewire component instance - improved version
            function getLivewireComponentEdit() {
            if (livewireComponentEdit) {
                return livewireComponentEdit;
            }
            
            // Try to find component via Livewire
            if (typeof Livewire !== 'undefined') {
                const componentEl = document.querySelector('[wire\\:id]');
                if (componentEl) {
                    const componentId = componentEl.getAttribute('wire:id');
                    livewireComponentEdit = Livewire.find(componentId);
                }
            }
            
            return livewireComponentEdit;
        }

            // Function to initialize Quill editor
            function initQuillEditor() {
            const editorEl = document.querySelector('#quill-edit-editor');

            if (editorEl && !quillEdit) {
                quillEdit = new Quill('#quill-edit-editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: '#quill-edit-toolbar'
                    },
                    placeholder: 'Tulis konten artikel di sini...',
                    formats: [
                        'header', 'font', 'size',
                        'bold', 'italic', 'underline', 'strike',
                        'color', 'background',
                        'list', 'bullet', 'align',
                        'link', 'image', 'code-block'
                    ]
                });

                // Get initial content from Livewire
                const initialContent = @js($content ?? '');
                if (initialContent) {
                    quillEdit.root.innerHTML = initialContent;
                } else {
                    // Fallback: try to get from hidden input
                    const contentInput = document.querySelector('input[wire\\:model="content"]');
                    if (contentInput && contentInput.value) {
                        quillEdit.root.innerHTML = contentInput.value;
                    }
                }

                // Sync content with Livewire (debounced)
                let syncTimeout;
                quillEdit.on('text-change', function() {
                    clearTimeout(syncTimeout);
                    syncTimeout = setTimeout(function() {
                        const content = quillEdit.root.innerHTML;
                        
                        // Update hidden input
                        const hiddenInput = document.querySelector('input[wire\\:model="content"]');
                        if (hiddenInput) {
                            hiddenInput.value = content;
                            // Trigger Livewire update
                            hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                        }

                        // Also sync directly to Livewire
                        const component = getLivewireComponentEdit();
                        if (component) {
                            component.set('content', content, false);
                        }
                    }, 300);
                });

                // Custom image handler
                const toolbar = quillEdit.getModule('toolbar');
                toolbar.addHandler('image', function() {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.onchange = function() {
                        const file = this.files[0];

                        if (!file) {
                            return;
                        }

                        const component = getLivewireComponentEdit();
                        if (component) {
                            component.call('uploadImage', file).then(result => {
                                if (result && result.error) {
                                    alert('Error uploading image: ' + result.error);
                                    return;
                                }

                                const range = quillEdit.getSelection(true);
                                const imageUrl = result?.url || result?.location || result;
                                quillEdit.insertEmbed(range.index, 'image', imageUrl);
                                quillEdit.setSelection(range.index + 1);
                            }).catch(error => {
                                alert('Error uploading image: ' + error);
                            });
                        } else {
                            alert('Livewire component tidak ditemukan. Silakan refresh halaman.');
                        }
                    };

                    input.click();
                });

                initAttempts = 0;
                return true;
            }
            return false;
        }

            // Poll for modal opening
            function checkForModal() {
            const modal = document.querySelector('#quill-edit-editor');

            if (modal && !quillEdit) {
                if (initAttempts < MAX_INIT_ATTEMPTS) {
                    initAttempts++;
                    if (!initQuillEditor()) {
                        setTimeout(checkForModal, 50);
                    }
                }
                } else if (!modal && quillEdit) {
                    // Modal closed, clean up
                    quillEdit = null;
                    initAttempts = 0;
                }
            }

            // Start checking for modal
            checkForModal();

            // Wait for Livewire to initialize
            function initializeEdit() {
                livewireComponentEdit = getLivewireComponentEdit();
            }

            if (typeof Livewire !== 'undefined') {
                document.addEventListener('livewire:init', initializeEdit);
                document.addEventListener('livewire:load', initializeEdit);
            }

            // Listen for modal opening
            if (typeof Livewire !== 'undefined') {
                Livewire.on('modal-opened', () => {
                    setTimeout(() => {
                        // Only initialize Quill editor if we're in edit mode
                        if (@js($isEditMode) && !quillEdit) {
                            initQuillEditor();
                        } else if (@js($isEditMode) && quillEdit) {
                            // Reload content if editor already exists
                            const initialContent = @js($content ?? '');
                            if (initialContent) {
                                quillEdit.root.innerHTML = initialContent;
                            }
                        }
                    }, 100);
                });
            }

            // Also listen for DOM updates from Livewire
            document.addEventListener('livewire:update', () => {
                livewireComponentEdit = getLivewireComponentEdit();
                setTimeout(() => {
                    // Only initialize editor if we're in edit mode
                    if (@js($isEditMode)) {
                        const editorEl = document.querySelector('#quill-edit-editor');
                        if (editorEl && !quillEdit) {
                            initQuillEditor();
                        }
                    }
                }, 100);
            });

            // Make quillEdit instance globally accessible to prevent undefined errors
            if (typeof window.quillEdit === 'undefined') {
                window.quillEdit = null;
            }

            // Global function to sync Quill content and update article
            window.updateArticle = function() {
                const component = getLivewireComponentEdit();
                
                if (!component) {
                    console.error('Livewire component not found');
                    alert('Terjadi kesalahan. Silakan refresh halaman.');
                    return false;
                }

                // Sync content from Quill to Livewire if Quill is available
                if (typeof quillEdit !== 'undefined' && quillEdit) {
                    const content = quillEdit.root.innerHTML;
                    
                    // Update hidden input
                    const hiddenInput = document.querySelector('input[wire\\:model="content"]');
                    if (hiddenInput) {
                        hiddenInput.value = content;
                        // Trigger Livewire update
                        hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }

                    // Update Livewire property and then call method
                    component.set('content', content, false).then(() => {
                        // Small delay to ensure content is synced
                        setTimeout(() => {
                            component.call('update').catch(error => {
                                console.error('Error calling update:', error);
                                alert('Terjadi kesalahan saat memperbarui artikel. Pastikan semua field sudah diisi dengan benar.');
                            });
                        }, 50);
                    }).catch(error => {
                        console.error('Error setting content:', error);
                        // Try to call method anyway
                        component.call('update').catch(err => {
                            console.error('Error calling update:', err);
                            alert('Terjadi kesalahan saat memperbarui artikel.');
                        });
                    });
                } else {
                    // Fallback - just call the method if Quill is not available
                    component.call('update').catch(error => {
                        console.error('Error calling update:', error);
                        alert('Terjadi kesalahan saat memperbarui artikel.');
                    });
                }
                
                return false; // Prevent default
            };
        })();

        // Delete Confirmation Modal
        const deleteType = '{{ $deleteType }}';
        const selectedCount = {{ count($selectedItems) }};

        function openDeleteModal() {
            const modal = document.getElementById('deleteConfirmModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteConfirmModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function confirmDelete() {
            if (deleteType === 'all') {
                @this.call('deleteAll').then(() => closeDeleteModal());
            } else {
                @this.call('deleteSelected').then(() => closeDeleteModal());
            }
        }
    </script>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-xl max-w-md w-full">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    Konfirmasi Hapus Artikel
                </h3>
                <button onclick="closeDeleteModal()" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="size-6 text-red-600 dark:text-red-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-neutral-900 dark:text-white mb-1">
                            <span x-text="deleteType === 'selected' ? `Hapus ${selectedCount} Artikel Terpilih?` : 'Hapus Semua Artikel?'"></span>
                        </p>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            <span x-text="deleteType === 'selected' ? 'Tindakan ini tidak dapat dibatalkan. Artikel yang dihapus akan hilang permanen.' : 'PERINGATAN: Ini akan menghapus SEMUA artikel di database. Tindakan ini tidak dapat dibatalkan!'"></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 p-6 border-t border-neutral-200 dark:border-neutral-700">
                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors"
                >
                    Batal
                </button>
                <button
                    type="button"
                    onclick="confirmDelete()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium text-sm transition-colors"
                >
                    <i data-lucide="trash" class="size-4"></i>
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>
