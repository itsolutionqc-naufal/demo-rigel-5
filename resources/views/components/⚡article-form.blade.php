<div>
    <!-- Flash Message -->
    @if(session()->has('success'))
        <div class="p-4 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 mb-6">
            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session()->get('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-lg bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 mb-6">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="alert-circle" class="size-5 text-red-600 dark:text-red-400"></i>
                <p class="text-sm font-medium text-red-800 dark:text-red-200">Terjadi kesalahan!</p>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Image Upload (Drag & Drop) -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                Gambar Utama
            </label>

            <!-- Upload Area -->
            <div
                class="relative mt-2"
                x-data="{
                    dragging: false,
                    preview: '{{ $image ?? '' }}',
                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        if (file) {
                            @this.set('image', file);
                            const reader = new FileReader();
                            reader.onload = (e) => this.preview = e.target.result;
                            reader.readAsDataURL(file);
                        }
                    }
                }"
            >
                <div
                    class="relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-lg cursor-pointer transition-colors"
                    :class="dragging ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-neutral-300 dark:border-neutral-600 hover:border-indigo-400 dark:hover:border-indigo-500'"
                    @dragenter.prevent="dragging = true"
                    @dragleave.prevent="dragging = false"
                    @dragover.prevent
                    @drop.prevent="
                        dragging = false;
                        const file = $event.dataTransfer.files[0];
                        if (file && file.type.startsWith('image/')) {
                            @this.set('image', file);
                            const reader = new FileReader();
                            reader.onload = (e) => preview = e.target.result;
                            reader.readAsDataURL(file);
                        }
                    "
                    @click="$refs.fileInput.click()"
                >
                    <!-- Preview Image -->
                    <template x-if="preview">
                        <div class="absolute inset-0 rounded-lg overflow-hidden">
                            <img :src="preview" alt="Preview" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-sm font-medium">Klik untuk mengganti</span>
                            </div>
                        </div>
                    </template>

                    <!-- Upload Placeholder -->
                    <template x-if="!preview">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i data-lucide="cloud-upload" class="size-12 text-neutral-400 dark:text-neutral-600 mb-3"></i>
                            <p class="mb-2 text-sm text-neutral-600 dark:text-neutral-400">
                                <span class="font-medium">Klik untuk upload</span> atau drag and drop
                            </p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-500">
                                PNG, JPG, GIF hingga 10MB
                            </p>
                        </div>
                    </template>
                </div>

                <input
                    type="file"
                    x-ref="fileInput"
                    class="hidden"
                    accept="image/*"
                    @change="handleFileSelect($event)"
                />

                <!-- Remove Image Button (when image exists) -->
                <template x-if="preview">
                    <button
                        type="button"
                        @click="
                            preview = '';
                            @this.set('image', null);
                            $refs.fileInput.value = '';
                        "
                        class="mt-3 inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                    >
                        <i data-lucide="trash-2" class="size-4"></i>
                        <span>Hapus Gambar</span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Title & Category -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <flux:input
                    wire:model.live="title"
                    label="Judul Artikel"
                    placeholder="Masukkan judul artikel"
                    required
                />
            </div>

            <div>
                <flux:input
                    wire:model="article_category"
                    label="Kategori"
                    placeholder="Contoh: Teknologi, Bisnis, dll"
                />
            </div>
        </div>

        <!-- Excerpt -->
        <div>
            <flux:textarea
                wire:model="excerpt"
                label="Excerpt (Ringkasan Singkat)"
                placeholder="Ringkasan singkat artikel..."
                rows="2"
            />
        </div>

        <!-- Content -->
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                Konten Artikel
            </label>
            <textarea
                id="content-editor"
                wire:model="content"
                placeholder="Tulis konten artikel di sini..."
                rows="12"
                class="w-full px-4 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            ></textarea>
        </div>

        <!-- Status -->
        <div>
            <flux:select wire:model="article_status" label="Status">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </flux:select>
        </div>

        <!-- Action Buttons (Fixed Bottom Right) -->
        <div class="flex items-center justify-end gap-3 sticky bottom-0 bg-white dark:bg-neutral-800 py-4 border-t border-neutral-200 dark:border-neutral-700">
            <a
                href="{{ route('articles') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors"
            >
                <i data-lucide="x" class="size-4"></i>
                <span>Batal</span>
            </a>

            <button
                type="button"
                wire:click="saveDraft"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg font-medium text-sm transition-colors"
            >
                <i data-lucide="archive" class="size-4"></i>
                <span>Simpan Draft</span>
            </button>

            <button
                type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors"
            >
                <i data-lucide="check" class="size-4"></i>
                <span>Publish Artikel</span>
            </button>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
        lucide.createIcons();

        // Initialize TinyMCE
        tinymce.init({
            selector: '#content-editor',
            height: 500,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic underline strikethrough | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'forecolor backcolor removeformat | image media link | ' +
                'fullscreen preview | help',
            toolbar_mode: 'sliding',
            image_advtab: true,
            image_uploadtab: true,
            automatic_uploads: true,
            images_upload_url: '{{ route("articles.upload") }}',
            images_upload_base_path: '/storage/articles',
            images_upload_credentials: true,
            file_picker_types: 'image',
            file_picker_callback: function(callback, value, meta) {
                // Create file input
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                input.onchange = function() {
                    const file = this.files[0];
                    const reader = new FileReader();
                    reader.onload = function() {
                        // Call Livewire to upload the file
                        @this.uploadImage(file).then(result => {
                            // Pass the uploaded image URL to TinyMCE
                            callback(result.url, { alt: file.name });
                        });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            },
            images_reuse_filename: true,
            images_upload_handler: function (blobInfo, success, failure) {
                const xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '{{ route("articles.upload") }}');
                xhr.onload = function() {
                    const json = JSON.parse(xhr.responseText);
                    if (json.location) {
                        success(json.location);
                    } else {
                        failure('Error uploading image');
                    }
                };
                xhr.onerror = function() {
                    failure('Error uploading image');
                };

                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            },
            content_style: 'https://cdn.jsdelivr.net/npm/tinymce@6/skins/content/default/content.min.css',
            setup: function (editor) {
                editor.on('init', function() {
                    // Enable image resizing
                    editor.on('NodeChange', function(e) {
                        if (e.element.nodeName === 'IMG') {
                            tinymce.DOM.setAttribs(e.element, 'style', 'max-width: 100%; height: auto;');
                        }
                    });
                });
            }
        });

        // Sync TinyMCE content with Livewire
        const editor = document.querySelector('#content-editor');
        if (editor) {
            editor.addEventListener('input', function() {
                @this.set('content', tinyMCE.get('content-editor').getContent());
            });
        }
    </script>
</div>
