<x-layouts::app :title="__('Edit Artikel')">
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Edit Artikel
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Memperbarui artikel "{{ $article->title }}"
                </p>
            </div>

            <a href="{{ route('articles') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors">
                <i data-lucide="arrow-left" class="size-4"></i>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Edit Form -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
            <form method="POST" action="{{ route('articles.update', $article) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Form Header -->
                <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        Informasi Artikel
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        Lengkapi formulir di bawah untuk memperbarui artikel
                    </p>
                </div>

                <!-- Form Body -->
                <div class="p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <flux:input
                            name="title"
                            label="Judul Artikel"
                            placeholder="Masukkan judul artikel"
                            :value="old('title', $article->title)"
                            required
                        />
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <flux:input
                            name="category"
                            label="Kategori"
                            placeholder="Contoh: Teknologi, Bisnis, dll"
                            :value="old('category', $article->category)"
                        />
                        @error('category')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <flux:textarea
                            name="excerpt"
                            label="Excerpt (Ringkasan Singkat)"
                            placeholder="Ringkasan singkat artikel yang akan ditampilkan di daftar..."
                            rows="3"
                            :value="old('excerpt', $article->excerpt)"
                        />
                        @error('excerpt')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content with Quill Editor -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Konten Artikel
                        </label>
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
                            <div id="quill-editor" style="min-height: 400px;">{!! old('content', $article->content) !!}</div>
                        </div>
                        <input type="hidden" name="content" id="content-input" value="{!! old('content', $article->content) !!}">
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
                                <label for="image-upload" class="flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm cursor-pointer transition-colors">
                                    <i data-lucide="upload" class="size-4 mr-2"></i>
                                    Pilih File
                                    <input id="image-upload" type="file" class="sr-only" name="image" accept="image/*">
                                </label>
                                @if($article->image)
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                        {{ basename($article->image) }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Current image preview -->
                            @if($article->image)
                                <div class="mt-4">
                                    <img src="{{ asset($article->image) }}" alt="Current image" class="h-48 rounded-lg object-contain border border-neutral-300 dark:border-neutral-600">
                                </div>
                            @endif
                            
                            <!-- New image preview -->
                            <div id="new-image-preview-container" class="mt-4 hidden">
                                <img id="new-image-preview" src="" alt="Preview" class="h-48 rounded-lg object-contain border border-neutral-300 dark:border-neutral-600">
                                <button
                                    type="button"
                                    onclick="removeNewImage()"
                                    class="mt-2 text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300"
                                >
                                    Hapus gambar
                                </button>
                            </div>

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
                        <flux:select name="status" label="Status Artikel" :value="old('status', $article->status)">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </flux:select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="p-6 border-t border-neutral-200 dark:border-neutral-700">
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 sm:space-x-reverse gap-3">
                        <a 
                            href="{{ route('articles') }}"
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
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Initialize Quill editor
        let quill;
	        document.addEventListener('DOMContentLoaded', function() {
	            quill = new Quill('#quill-editor', {
	                theme: 'snow',
	                modules: {
	                    toolbar: '#quill-toolbar'
	                },
	                placeholder: 'Tulis konten artikel di sini...'
	            });

	            // Custom image upload handler for Quill (upload file -> insert URL)
	            const toolbar = quill.getModule('toolbar');
	            toolbar.addHandler('image', function() {
	                const input = document.createElement('input');
	                input.setAttribute('type', 'file');
	                input.setAttribute('accept', 'image/*');

	                input.onchange = async function() {
	                    const file = input.files?.[0];
	                    if (!file) return;

	                    try {
	                        const formData = new FormData();
	                        formData.append('file', file);

	                        const response = await fetch('{{ route('articles.upload') }}', {
	                            method: 'POST',
	                            body: formData,
	                            headers: {
	                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
	                                'Accept': 'application/json',
	                                'X-Requested-With': 'XMLHttpRequest'
	                            }
	                        });

	                        const result = await response.json();
	                        if (!response.ok) {
	                            throw new Error(result?.message || result?.error || 'Gagal upload gambar');
	                        }

	                        const imageUrl = result?.url || result?.location;
	                        const range = quill.getSelection(true);
	                        quill.insertEmbed(range.index, 'image', imageUrl);
	                        quill.setSelection(range.index + 1);
	                    } catch (error) {
	                        console.error(error);
	                        alert('Gagal upload gambar: ' + (error?.message || error));
	                    }
	                };

	                input.click();
	            });

            // Set initial content
            const initialContent = document.getElementById('content-input').value;
            if (initialContent) {
                quill.root.innerHTML = initialContent;
            }

            // Update hidden input on text change
            quill.on('text-change', function() {
                document.getElementById('content-input').value = quill.root.innerHTML;
	        });
        });

        // Handle image preview
        document.getElementById('image-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('new-image-preview').src = event.target.result;
                    document.getElementById('new-image-preview-container').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Remove new image function
        function removeNewImage() {
            document.getElementById('image-upload').value = '';
            document.getElementById('new-image-preview-container').classList.add('hidden');
            document.getElementById('new-image-preview').src = '';
        }
    </script>
</x-layouts::app>
