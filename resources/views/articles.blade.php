<x-layouts::app :title="__('Manajemen Artikel')">
    <div x-data="{
        totalArticles: {{ \App\Models\Article::count() }},
        publishedArticles: {{ \App\Models\Article::where('status', 'published')->count() }},
        draftArticles: {{ \App\Models\Article::where('status', 'draft')->count() }},
        totalViews: {{ \App\Models\Article::sum('views') }}
    }" class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header Section with Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Manajemen Artikel
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Kelola semua artikel Anda di sini
                </p>
            </div>

            <button
                type="button"
                onclick="openArticleModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors"
            >
                <i data-lucide="plus" class="size-4"></i>
                <span>Tambah Artikel</span>
            </button>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Artikel -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-800 dark:to-neutral-900 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Artikel</p>
                        <p class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white" x-text="totalArticles"></p>
                    </div>
                    <div class="rounded-lg bg-neutral-300 dark:bg-neutral-600 p-3">
                        <i data-lucide="file-text" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Artikel Published -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/30 dark:to-emerald-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Artikel Published</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-900 dark:text-emerald-100" x-text="publishedArticles"></p>
                    </div>
                    <div class="rounded-lg bg-emerald-400 dark:bg-emerald-600 p-3">
                        <i data-lucide="check-circle" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Artikel Draft -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/30 dark:to-amber-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-700 dark:text-amber-300">Artikel Draft</p>
                        <p class="mt-2 text-3xl font-bold text-amber-900 dark:text-amber-100" x-text="draftArticles"></p>
                    </div>
                    <div class="rounded-lg bg-amber-400 dark:bg-amber-600 p-3">
                        <i data-lucide="file" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Total Views -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Views</p>
                        <p class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100" x-text="totalViews.toLocaleString()"></p>
                    </div>
                    <div class="rounded-lg bg-blue-400 dark:bg-blue-600 p-3">
                        <i data-lucide="eye" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Article Management Component -->
        <livewire:article-management />
    </div>

    <!-- Article Creation Modal -->
    <div id="article-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    Buat Artikel Baru
                </h3>
                <button onclick="closeArticleModal()" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="article-form" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Judul Artikel
                            </label>
                            <input
                                type="text"
                                name="title"
                                id="article-title"
                                class="w-full px-4 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Masukkan judul artikel"
                                required
                            />
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Kategori
                            </label>
                            <input
                                type="text"
                                name="category"
                                id="article-category"
                                class="w-full px-4 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Contoh: Teknologi, Bisnis, dll"
                            />
                        </div>

                        <!-- Excerpt -->
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Excerpt (Ringkasan Singkat)
                            </label>
                            <textarea
                                name="excerpt"
                                id="article-excerpt"
                                rows="3"
                                class="w-full px-4 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Ringkasan singkat artikel yang akan ditampilkan di daftar..."
                            ></textarea>
                        </div>

                        <!-- Content -->
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Konten Artikel
                            </label>
                            <div id="quill-editor-modal" style="height: 300px;"></div>
                            <input type="hidden" name="content" id="article-content" />
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Gambar Utama
                            </label>
                            <div class="mt-1">
                                <!-- Simple file input -->
                                <div class="flex items-center space-x-4">
                                    <label for="article-image-upload" class="flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm cursor-pointer transition-colors">
                                        <i data-lucide="upload" class="size-4 mr-2"></i>
                                        Pilih File
                                        <input id="article-image-upload" type="file" class="sr-only" name="image" accept="image/*">
                                    </label>
                                    <span id="image-filename" class="text-sm text-neutral-600 dark:text-neutral-400 hidden"></span>
                                </div>

                                <!-- Preview image below the input -->
                                <div id="image-preview-container" class="mt-4 hidden">
                                    <img id="image-preview" src="" alt="Preview" class="h-48 rounded-lg object-contain border border-neutral-300 dark:border-neutral-600">
                                    <button
                                        type="button"
                                        onclick="removeImage()"
                                        class="mt-2 text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300"
                                    >
                                        Hapus gambar
                                    </button>
                                </div>

                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                                    Format: PNG, JPG, GIF | Maksimal ukuran: 10MB
                                </p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Status Artikel
                            </label>
                            <select
                                name="status"
                                id="article-status"
                                class="w-full px-4 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            >
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-2 p-6 border-t border-neutral-200 dark:border-neutral-700">
                <button
                    type="button"
                    onclick="closeArticleModal()"
                    class="px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors"
                >
                    Batal
                </button>
                <button
                    type="button"
                    onclick="submitArticleForm('draft')"
                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 dark:bg-amber-500 dark:hover:bg-amber-600 text-white rounded-lg font-medium text-sm transition-colors"
                >
                    Simpan Draft
                </button>
                <button
                    type="button"
                    onclick="submitArticleForm('published')"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors"
                >
                    Publikasikan Artikel
                </button>
            </div>
        </div>
    </div>

    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Initialize Quill editor for modal
        let quillModal;
	        document.addEventListener('DOMContentLoaded', function() {
	            quillModal = new Quill('#quill-editor-modal', {
	                theme: 'snow',
	                modules: {
                    toolbar: [
                        [{'header': [1, 2, 3, false]}],
                        ['bold', 'italic', 'underline'],
                        [{'list': 'ordered'}, {'list': 'bullet'}],
                        ['link', 'image'],
                        ['clean']
                    ]
                },
	                placeholder: 'Tulis konten artikel di sini...'
	            });

	            // Custom image upload handler for Quill (upload file -> insert URL)
	            const toolbar = quillModal.getModule('toolbar');
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
	                        const range = quillModal.getSelection(true);
	                        quillModal.insertEmbed(range.index, 'image', imageUrl);
	                        quillModal.setSelection(range.index + 1);
	                    } catch (error) {
	                        console.error(error);
	                        alert('Gagal upload gambar: ' + (error?.message || error));
	                    }
	                };

	                input.click();
	            });
	        });

        // Open modal function
        function openArticleModal() {
            document.getElementById('article-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Close modal function
        function closeArticleModal() {
            document.getElementById('article-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';

            // Reset form
            document.getElementById('article-form').reset();
            quillModal.setText('');
            document.getElementById('article-content').value = '';
            document.getElementById('image-filename').classList.add('hidden');
            document.getElementById('image-preview-container').classList.add('hidden');
            document.getElementById('image-preview').src = '';
        }

        // Handle image preview
        document.getElementById('article-image-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('image-filename').textContent = file.name;
                document.getElementById('image-filename').classList.remove('hidden');

                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('image-preview').src = event.target.result;
                    document.getElementById('image-preview-container').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Remove image function
        function removeImage() {
            document.getElementById('article-image-upload').value = '';
            document.getElementById('image-filename').classList.add('hidden');
            document.getElementById('image-preview-container').classList.add('hidden');
            document.getElementById('image-preview').src = '';
        }

        // Submit article form function
	        async function submitArticleForm(status) {
            // Update hidden content field with Quill content
            document.getElementById('article-content').value = quillModal.root.innerHTML;

            // Set status
            document.getElementById('article-status').value = status;

            const formData = new FormData(document.getElementById('article-form'));

	            try {
	                const response = await fetch('{{ route('articles.store') }}', {
	                    method: 'POST',
	                    body: formData,
	                    headers: {
	                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
	                        'Accept': 'application/json',
	                        'X-Requested-With': 'XMLHttpRequest'
	                    }
	                });

	                const contentType = response.headers.get('content-type') || '';
	                const result = contentType.includes('application/json')
	                    ? await response.json()
	                    : { message: await response.text() };

	                if (response.ok) {
                    // Show success message
                    alert('Artikel berhasil ' + (status === 'published' ? 'dipublikasikan!' : 'disimpan sebagai draft!'));

                    // Close modal and refresh the page or update the article list
                    closeArticleModal();

                    // Optionally reload the page to show the new article
                    location.reload();
	                } else {
	                    // Show error message
	                    alert('Error: ' + (result.message || 'Terjadi kesalahan saat menyimpan artikel'));
	                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan artikel');
            }
        }

        // Close modal when clicking outside
        document.getElementById('article-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeArticleModal();
            }
        });

        // Function to confirm deletion with custom modal
        function confirmDelete(articleId) {
            // Create custom modal HTML
            const modalHtml = `
                <div id="custom-delete-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-20 backdrop-blur-sm">
                    <div class="bg-white dark:bg-neutral-800 bg-opacity-95 rounded-xl shadow-xl max-w-md w-full p-6 transform transition-transform scale-95 animate-in fade-in-90 zoom-in-90">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                                <i data-lucide="alert-triangle" class="size-5 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                    Hapus Artikel?
                                </h3>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">
                                    Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-2 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                            <button
                                id="cancel-delete-btn"
                                class="px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                id="confirm-delete-btn"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white rounded-lg font-medium text-sm transition-colors"
                            >
                                Ya, Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `;

            // Add modal to the page
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Initialize lucide icons
            lucide.createIcons();

            // Get modal elements
            const modal = document.getElementById('custom-delete-modal');
            const confirmBtn = document.getElementById('confirm-delete-btn');
            const cancelBtn = document.getElementById('cancel-delete-btn');

            // Close modal function
            function closeModal() {
                if (modal) {
                    modal.remove();
                }
            }

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Close modal when clicking cancel
            cancelBtn.addEventListener('click', closeModal);

            // Handle delete confirmation
            confirmBtn.addEventListener('click', function() {
                // Send DELETE request to the server
                fetch(`/articles/${articleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert(data.message);
                        // Close modal and refresh the page
                        closeModal();
                        location.reload();
                    } else {
                        // Show error message
                        alert('Error: ' + (data.message || 'Terjadi kesalahan saat menghapus artikel'));
                        closeModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus artikel');
                    closeModal();
                });
            });
        }
    </script>
</x-layouts::app>
