<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
            Profil Saya
        </h2>
    </div>

    {{-- Profile Photo Section --}}
    <div class="flex flex-col items-center">
        <div class="relative">
            <div class="h-24 w-24 overflow-hidden rounded-full border-4 border-white shadow-xl dark:border-neutral-700">
                @if(auth()->user()->avatar && file_exists(public_path(auth()->user()->avatar)))
                    <img id="profileAvatar" src="{{ asset(auth()->user()->avatar) }}"
                         alt="Profile"
                         class="h-full w-full object-cover">
                @else
                    <img id="profileAvatar" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=000&color=fff"
                         alt="Profile"
                         class="h-full w-full object-cover">
                @endif
            </div>
            <button type="button" onclick="openUploadModal()" class="absolute bottom-0 right-0 rounded-full bg-neutral-900 p-2 text-white shadow-md hover:bg-neutral-700 dark:bg-white dark:text-black dark:hover:bg-neutral-200 transition-colors border-2 border-white dark:border-neutral-700">
                <i data-lucide="camera" class="size-4"></i>
            </button>
        </div>
        <p class="mt-4 text-center text-xs text-neutral-500 dark:text-neutral-400">
            Ketuk ikon kamera untuk mengubah foto
        </p>
    </div>

    {{-- Edit Form --}}
    <form id="profileForm" onsubmit="handleProfileUpdate(event)" class="space-y-4">
        @csrf

        <div class="space-y-2">
            <label for="username" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                Username
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <span class="text-neutral-500">@</span>
                </div>
                <input type="text"
                       id="username"
                       name="username"
                       value="{{ auth()->user()->username ?? strtolower(str_replace(' ', '', auth()->user()->name)) }}"
                       class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2.5 pl-8 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-600 dark:focus:border-white dark:focus:ring-white"
                       placeholder="username">
            </div>
        </div>

        <div class="space-y-2">
            <label for="name" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                Nama Lengkap
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   value="{{ auth()->user()->name }}"
                   class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-600 dark:focus:border-white dark:focus:ring-white"
                   placeholder="Masukkan nama lengkap">
        </div>

        <div class="space-y-2">
            <label for="email" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                Email
            </label>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ auth()->user()->email }}"
                   class="w-full rounded-lg border border-neutral-300 bg-neutral-50 px-4 py-2.5 text-sm text-neutral-500 cursor-not-allowed dark:border-neutral-700 dark:bg-neutral-900/50 dark:text-neutral-400"
                   readonly>
        </div>

        <div class="pt-4">
            <button type="submit" id="saveProfileBtn" class="w-full rounded-lg bg-neutral-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-neutral-800 dark:bg-white dark:text-black dark:hover:bg-neutral-200 flex items-center justify-center gap-2">
                <i data-lucide="save" class="size-4"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>

    <div class="pt-2">
        <button type="button" onclick="showLogoutConfirmation()" class="w-full rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-600 transition hover:bg-red-100 dark:border-red-900/30 dark:bg-red-900/10 dark:text-red-400 dark:hover:bg-red-900/20 flex items-center justify-center gap-2">
            <i data-lucide="log-out" class="size-4"></i>
            Keluar Aplikasi
        </button>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="w-full max-w-sm rounded-xl bg-white p-6 shadow-xl dark:bg-neutral-800">
        <!-- Modal Header -->
        <div class="mb-4 text-center">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                <i data-lucide="log-out" class="size-6 text-red-600 dark:text-red-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                Keluar Aplikasi
            </h3>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Apakah Anda yakin ingin keluar dari aplikasi?
            </p>
        </div>

        <!-- Modal Actions -->
        <div class="flex gap-3">
            <button type="button" onclick="hideLogoutConfirmation()" class="flex-1 rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600">
                Batal
            </button>
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-700 flex items-center justify-center gap-2">
                    <i data-lucide="log-out" class="size-4"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Upload Avatar Modal -->
<div id="uploadAvatarModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl dark:bg-neutral-800">
        <!-- Modal Header -->
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                Upload Foto Profil
            </h3>
            <button type="button" onclick="closeUploadModal()" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200">
                <i data-lucide="x" class="size-5"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="space-y-4">
            <!-- Step 1: Select Image -->
            <div id="selectImageStep">
                <label for="avatarInput" class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-neutral-300 bg-neutral-50 py-12 dark:border-neutral-600 dark:bg-neutral-700/50">
                    <i data-lucide="image" class="mb-3 size-12 text-neutral-400"></i>
                    <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Klik untuk pilih gambar</p>
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">PNG, JPG, GIF, WEBP (Max 5MB)</p>
                </label>
                <input type="file" id="avatarInput" accept="image/*" class="hidden" onchange="handleImageSelect(event)">
            </div>

            <!-- Step 2: Crop Image -->
            <div id="cropImageStep" class="hidden">
                <div class="mb-4">
                    <canvas id="avatarCanvas" class="max-h-64 w-full rounded-lg bg-neutral-100 dark:bg-neutral-700"></canvas>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="resetCrop()" class="flex-1 rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600">
                        Batal
                    </button>
                    <button type="button" onclick="saveCrop()" class="flex-1 rounded-lg bg-neutral-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-black dark:hover:bg-neutral-200">
                        Potong & Simpan
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loadingStep" class="hidden py-8 text-center">
                <div class="mb-3 flex justify-center">
                    <div class="size-8 animate-spin rounded-full border-4 border-neutral-300 border-t-neutral-900 dark:border-neutral-600 dark:border-t-white"></div>
                </div>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">Mengunggah foto...</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

<script>
    let cropper = null;
    let selectedFile = null;

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

    // Show logout confirmation modal
    function showLogoutConfirmation() {
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        initLucideIcons();
    }

    // Hide logout confirmation modal
    function hideLogoutConfirmation() {
        const modal = document.getElementById('logoutModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        const logoutModal = document.getElementById('logoutModal');
        const uploadModal = document.getElementById('uploadAvatarModal');
        if (e.target === logoutModal) {
            hideLogoutConfirmation();
        }
        if (e.target === uploadModal) {
            closeUploadModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideLogoutConfirmation();
            closeUploadModal();
        }
    });

    // Open upload modal
    function openUploadModal() {
        const modal = document.getElementById('uploadAvatarModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        initLucideIcons();
    }

    // Close upload modal
    function closeUploadModal() {
        const modal = document.getElementById('uploadAvatarModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        resetCrop();
        document.getElementById('selectImageStep').classList.remove('hidden');
        document.getElementById('cropImageStep').classList.add('hidden');
        document.getElementById('loadingStep').classList.add('hidden');
    }

    // Handle image selection
    function handleImageSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showToast('Ukuran file terlalu besar. Maksimal 5MB', 'error');
            return;
        }

        selectedFile = file;
        const reader = new FileReader();
        reader.onload = function(e) {
            // Show crop step
            document.getElementById('selectImageStep').classList.add('hidden');
            document.getElementById('cropImageStep').classList.remove('hidden');

            // Initialize cropper
            const canvas = document.getElementById('avatarCanvas');
            const context = canvas.getContext('2d');
            const img = new Image();
            img.onload = function() {
                // Set canvas size to image size
                canvas.width = img.width;
                canvas.height = img.height;
                
                // Draw image on canvas
                context.drawImage(img, 0, 0);
                
                // Initialize Cropper.js
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(canvas, {
                    aspectRatio: 1, // Square crop
                    viewMode: 1,
                    preview: '.img-preview',
                    autoCropArea: 0.8,
                });
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Reset crop
    function resetCrop() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        selectedFile = null;
        document.getElementById('avatarInput').value = '';
        document.getElementById('selectImageStep').classList.remove('hidden');
        document.getElementById('cropImageStep').classList.add('hidden');
    }

    // Save cropped image
    function saveCrop() {
        if (!cropper) return;

        // Show loading
        document.getElementById('cropImageStep').classList.add('hidden');
        document.getElementById('loadingStep').classList.remove('hidden');

        // Get cropped canvas
        const croppedCanvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400,
        });

        // Convert to blob
        croppedCanvas.toBlob(function(blob) {
            // Create FormData
            const formData = new FormData();
            formData.append('image', blob, 'avatar.jpg');

            // Upload to server
            fetch('{{ route("mobile.upload-avatar") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update profile avatar
                    document.getElementById('profileAvatar').src = data.avatar_url + '?t=' + new Date().getTime();
                    showToast(data.message, 'success');
                    closeUploadModal();
                } else {
                    showToast(data.message || 'Gagal mengunggah foto', 'error');
                    resetCrop();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat mengunggah foto', 'error');
                resetCrop();
            });
        }, 'image/jpeg', 0.9);
    }

    // Handle profile update
    function handleProfileUpdate(event) {
        event.preventDefault();
        
        const btn = document.getElementById('saveProfileBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<div class="size-4 animate-spin rounded-full border-2 border-white border-t-transparent"></div> Menyimpan...';

        const formData = new FormData(event.target);
        
        fetch('{{ route("mobile.update-profile") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Profil berhasil diperbarui', 'success');
            } else {
                showToast(data.message || 'Gagal memperbarui profil', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }

    // Show toast notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 rounded-lg px-4 py-3 text-sm font-medium shadow-lg transition-all transform translate-x-0 ${
            type === 'success' 
                ? 'bg-emerald-600 text-white' 
                : type === 'error' 
                    ? 'bg-red-600 text-white' 
                    : 'bg-neutral-800 text-white'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
