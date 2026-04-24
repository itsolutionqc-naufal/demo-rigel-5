<div>
    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <style>
        /* Dark Mode Styles for Quill Editor */
        .ql-toolbar.ql-snow {
            border-color: #4b5563 !important;
            background-color: #1f2937 !important;
        }

        .ql-toolbar.ql-snow .ql-formats {
            border-color: #4b5563 !important;
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

        .ql-toolbar.qlnow button:hover .ql-stroke,
        .ql-toolbar.ql-snow button.ql-active .ql-stroke {
            stroke: #6366f1 !important;
        }

        .ql-toolbar.ql-snow .ql-fill {
            fill: #9ca3af !important;
        }

        .ql-toolbar.ql-snow button:hover .ql-fill,
        .ql-toolbar.ql-snow button.ql-active .ql-fill {
            fill: #6366f1 !important;
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

    <div class="space-y-6" wire:ignore.self>
        <!-- Flash Messages -->
        @if(session()->has('success'))
            <div class="p-4 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800">
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session()->get('success') }}</p>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="p-4 rounded-lg bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800">
                <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session()->get('error') }}</p>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="p-4 rounded-lg bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800">
                <ul class="text-sm font-medium text-red-800 dark:text-red-200 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
            <!-- Form Header -->
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    Informasi Artikel
                </h2>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Lengkapi formulir di bawah untuk membuat artikel baru
                </p>
            </div>

            <!-- Form Body -->
            <div class="p-6 space-y-6">
                <!-- Title -->
                <div>
                    <flux:input
                        wire:model.live="title"
                        label="Judul Artikel"
                        placeholder="Masukkan judul artikel"
                        required
                    />
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <flux:input
                        wire:model="article_category"
                        label="Kategori"
                        placeholder="Contoh: Teknologi, Bisnis, dll"
                    />
                    @error('article_category')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Excerpt -->
                <div>
                    <flux:textarea
                        wire:model="excerpt"
                        label="Excerpt (Ringkasan Singkat)"
                        placeholder="Ringkasan singkat artikel yang akan ditampilkan di daftar..."
                        rows="3"
                    />
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content with Quill Editor -->
                <div wire:ignore>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Konten Artikel
                    </label>
                    <!-- Hidden input for Livewire binding -->
                    <input type="hidden" wire:model="content">

                    <!-- Quill Editor Container -->
                    <div id="quill-editor-container" class="rounded-lg overflow-hidden border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-900">
                        <!-- Toolbar -->
                        <div id="quill-toolbar" class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800">
                            <span class="ql-formats">
                                <select class="ql-header">
                                    <option value="1">Heading 1</option>
                                    <option value="2">Heading 2</option>
                                    <option value="3">Heading 3</option>
                                    <option value="0">Normal</option>
                                </select>
                                <select class="ql-font">
                                    <option selected>Sans Serif</option>
                                    <option value="serif">Serif</option>
                                    <option value="monospace">Monospace</option>
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
                                    <option value="center">Center</option>
                                    <option value="right">Right</option>
                                    <option value="justify">Justify</option>
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
                        <!-- Editor -->
                        <div id="quill-editor" style="min-height: 400px;"></div>
                    </div>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Gambar Utama
                    </label>
                    <div class="mt-1">
                        <!-- Simple file input -->
                        <div class="flex items-center space-x-4">
                            <label for="file-upload" class="flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm cursor-pointer transition-colors">
                                <i data-lucide="upload" class="size-4 mr-2"></i>
                                Pilih File
                                <input id="file-upload" type="file" class="sr-only" wire:model="image" accept="image/*">
                            </label>
                            @if($image)
                                <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                    @if(is_string($image))
                                        {{ basename($image) }}
                                    @else
                                        {{ $image->getClientOriginalName() }}
                                    @endif
                                </span>
                            @endif
                        </div>

                        <!-- Preview image below the input -->
                        @if($image)
                            <div class="mt-4">
                                @if(is_string($image))
                                    <img src="{{ $image }}" alt="Preview" class="h-48 rounded-lg object-contain border border-neutral-300 dark:border-neutral-600">
                                @else
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-48 rounded-lg object-contain border border-neutral-300 dark:border-neutral-600">
                                @endif

                                <button
                                    wire:click="$remove('image')"
                                    type="button"
                                    class="mt-2 text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300"
                                >
                                    Hapus gambar
                                </button>
                            </div>
                        @endif

                        <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                            Format: PNG, JPG, GIF | Maksimal ukuran: 10MB
                        </p>
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <flux:select wire:model="article_status" label="Status Artikel">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </flux:select>
                    @error('article_status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview URL -->
                @if($title)
                    <div class="p-4 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-1">Preview URL:</p>
                        <p class="text-sm font-mono text-neutral-700 dark:text-neutral-300 break-all">
                            /articles/{{ Str::slug($title) }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Form Footer -->
            <div class="p-6 border-t border-neutral-200 dark:border-neutral-700">
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 sm:space-x-reverse gap-3">
                    <!-- Save Draft Button -->
                    <button
                        type="button"
                        onclick="submitArticle('saveDraft')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                    >
                        <i data-lucide="archive" class="size-4"></i>
                        <span wire:loading.remove wire:target="saveDraft">Simpan Draft</span>
                        <span wire:loading wire:target="saveDraft">Memproses...</span>
                    </button>

                    <!-- Publish Button -->
                    <button
                        type="button"
                        onclick="submitArticle('save')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                    >
                        <i data-lucide="check" class="size-4"></i>
                        <span wire:loading.remove wire:target="save">Publikasikan Artikel</span>
                        <span wire:loading wire:target="save">Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quill Editor JS -->
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
        (function() {
            'use strict';

            lucide.createIcons();

            // Initialize Quill Editor and store it globally
            let quill;
            let livewireComponent = null;

            // Function to get the Livewire component using Alpine.js $wire (for Livewire v3)
            function getLivewireComponent() {
                // Try to find the component using the newer Livewire v3 approach
                if (typeof Alpine !== 'undefined' && Alpine.$data) {
                    const el = document.querySelector('[wire\\:id]');
                    if (el && Alpine.$data(el)?.__instance) {
                        return Alpine.$data(el).__instance;
                    }
                }

                // Fallback to traditional method
                if (typeof window.Livewire === 'undefined') {
                    return null;
                }

                const componentEl = document.querySelector('[wire\\:id]');
                if (!componentEl) {
                    return null;
                }

                const id = componentEl.getAttribute('wire:id');
                if (!id) {
                    return null;
                }

                try {
                    return window.Livewire.find(id);
                } catch (error) {
                    console.warn('Error finding Livewire component:', error);
                    return null;
                }
            }

            // Initialize Quill editor
            function initQuill() {
                const editorEl = document.getElementById('quill-editor');
                if (!editorEl || quill) {
                    return;
                }

                quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: '#quill-toolbar'
                    },
                    placeholder: 'Tulis konten artikel lengkap di sini...',
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
                    quill.root.innerHTML = initialContent;
                }

                // Sync content with Livewire on change (debounced)
                let syncTimeout;
                quill.on('text-change', function() {
                    clearTimeout(syncTimeout);
                    syncTimeout = setTimeout(function() {
                        const content = quill.root.innerHTML;

                        // Update hidden input
                        const hiddenInput = document.querySelector('input[wire\\:model="content"]');
                        if (hiddenInput) {
                            hiddenInput.value = content;
                            hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                        }

                        // Also sync directly to Livewire
                        const component = getLivewireComponent();
                        if (component) {
                            if (typeof component.set === 'function') {
                                component.set('content', content, false);
                            } else {
                                // For Livewire v3, use the $wire property
                                if (component.$wire) {
                                    component.$wire.set('content', content);
                                }
                            }
                        }
                    }, 300);
                });

                // Custom image handler
                const toolbar = quill.getModule('toolbar');
                toolbar.addHandler('image', function() {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.onchange = function() {
                        const file = this.files[0];
                        if (!file) {
                            return;
                        }

                        const component = getLivewireComponent();
                        if (component) {
                            let promise;
                            if (typeof component.call === 'function') {
                                promise = component.call('uploadImage', file);
                            } else if (component.$wire && typeof component.$wire.uploadImage === 'function') {
                                promise = component.$wire.uploadImage(file);
                            }

                            if (promise) {
                                promise.then(result => {
                                    if (result && result.error) {
                                        alert('Error uploading image: ' + result.error);
                                        return;
                                    }

                                    const range = quill.getSelection(true);
                                    const imageUrl = result?.url || result?.location || result;
                                    quill.insertEmbed(range.index, 'image', imageUrl);
                                    quill.setSelection(range.index + 1);
                                }).catch(error => {
                                    alert('Error uploading image: ' + error);
                                });
                            }
                        } else {
                            alert('Livewire component tidak ditemukan. Silakan refresh halaman.');
                        }
                    };

                    input.click();
                });
            }

            // Wait for DOM and Livewire to be ready
            function initialize() {
                // Initialize Quill
                initQuill();
            }

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initialize);
            } else {
                initialize();
            }

            // Re-initialize when Livewire updates
            document.addEventListener('livewire:init', function() {
                initialize();
            });

            document.addEventListener('livewire:load', function() {
                // Get Livewire component after it's loaded
                livewireComponent = getLivewireComponent();
            });

            // Global function to sync Quill content and submit
            window.submitArticle = function(method) {
                // Get component fresh each time since it might change
                const component = getLivewireComponent();

                if (!component) {
                    console.error('Livewire component not found');
                    alert('Terjadi kesalahan. Silakan refresh halaman.');
                    return false;
                }

                // Sync content from Quill to Livewire if Quill is available
                if (typeof quill !== 'undefined' && quill) {
                    const content = quill.root.innerHTML;

                    // Update hidden input
                    const hiddenInput = document.querySelector('input[wire\\:model="content"]');
                    if (hiddenInput) {
                        hiddenInput.value = content;
                        hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }

                    // Update Livewire property and then call method
                    let setPromise;
                    if (typeof component.set === 'function') {
                        setPromise = component.set('content', content, false);
                    } else if (component.$wire) {
                        component.$wire.set('content', content);
                        setPromise = Promise.resolve(); // $wire.set is synchronous in v3
                    } else {
                        setPromise = Promise.resolve(); // fallback
                    }

                    setPromise.then(() => {
                        setTimeout(() => {
                            let callPromise;
                            if (typeof component.call === 'function') {
                                callPromise = component.call(method);
                            } else if (component.$wire && typeof component.$wire[method] === 'function') {
                                callPromise = component.$wire[method]();
                            }

                            if (callPromise) {
                                callPromise.then(() => {
                                    // Success callback - nothing needed here
                                }).catch(error => {
                                    console.error('Error calling method:', error);
                                    alert('Terjadi kesalahan saat menyimpan artikel. Pastikan semua field sudah diisi dengan benar.');
                                });
                            } else {
                                console.error('Method not found on component:', method);
                                alert('Terjadi kesalahan saat menyimpan artikel.');
                            }
                        }, 50);
                    }).catch(error => {
                        console.error('Error setting content:', error);
                        let callPromise;
                        if (typeof component.call === 'function') {
                            callPromise = component.call(method);
                        } else if (component.$wire && typeof component.$wire[method] === 'function') {
                            callPromise = component.$wire[method]();
                        }

                        if (callPromise) {
                            callPromise.then(() => {
                                // Success callback - nothing needed here
                            }).catch(err => {
                                console.error('Error calling method:', err);
                                alert('Terjadi kesalahan saat menyimpan artikel.');
                            });
                        }
                    });
                } else {
                    // Fallback - just call the method if Quill is not available
                    let callPromise;
                    if (typeof component.call === 'function') {
                        callPromise = component.call(method);
                    } else if (component.$wire && typeof component.$wire[method] === 'function') {
                        callPromise = component.$wire[method]();
                    }

                    if (callPromise) {
                        callPromise.then(() => {
                            // Success callback - nothing needed here
                        }).catch(error => {
                            console.error('Error calling method:', error);
                            alert('Terjadi kesalahan saat menyimpan artikel.');
                        });
                    } else {
                        console.error('Method not found on component:', method);
                        alert('Terjadi kesalahan saat menyimpan artikel.');
                    }
                }

                return false; // Prevent default
            };
        })();
    </script>
</div>
