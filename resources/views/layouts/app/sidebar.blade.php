<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        
        <!-- jQuery (load first) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        @php
            $homeRouteName = auth()->user()->homeRouteName();
            $avatarUploadRoute = auth()->user()->isAdmin()
                ? route('admin.upload-avatar')
                : route('profile.upload-avatar');
            $avatarUploadTitle = auth()->user()->isAdmin()
                ? 'Upload Foto Profil Admin'
                : 'Upload Foto Profil';
        @endphp

        <flux:sidebar sticky collapsible="mobile" class="flex h-screen flex-col overflow-hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route($homeRouteName) }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="min-h-0 flex-1 overflow-y-auto">
                <flux:sidebar.group :heading="__('Umum')" class="grid mt-6">
                    <flux:sidebar.item
                        icon="layout-grid"
                        :href="route($homeRouteName)"
                        :current="request()->routeIs('dashboard') || request()->routeIs('marketing.dashboard')"
                        wire:navigate
                    >
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                @if(auth()->user()->isAdmin())
                <flux:sidebar.group :heading="__('Master Data')" class="grid mt-4">
                    <flux:sidebar.item icon="newspaper" :href="route('articles')" :current="request()->routeIs('articles')" wire:navigate>
                        {{ __('Manajemen Artikel') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.*')" wire:navigate>
                        {{ __('Manajemen User') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="squares-2x2"
                        :href="route('services.index')"
                        :current="request()->routeIs('services.*') && !(request()->routeIs('services.index') && request('category') === 'talent_hunter')"
                        wire:navigate
                    >
                        {{ __('Layanan Aplikasi') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="magnifying-glass"
                        :href="route('services.index', ['category' => 'talent_hunter'])"
                        :current="request()->routeIs('services.index') && request('category') === 'talent_hunter'"
                        wire:navigate
                    >
                        {{ __('Talent Hunter') }}
                    </flux:sidebar.item>

                    @if(auth()->check() && auth()->user()->isAdmin() && \Illuminate\Support\Facades\Route::has('admin.host-submissions.index'))
                        <flux:sidebar.item
                            icon="user-group"
                            :href="route('admin.host-submissions.index')"
                            :current="request()->routeIs('admin.host-submissions.*')"
                            wire:navigate
                        >
                            {{ __('Submit Host') }}
                        </flux:sidebar.item>
                    @endif
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Keuangan')" class="grid mt-4">
                    <flux:sidebar.item icon="credit-card" :href="route('wallet.index')" :current="request()->routeIs('wallet.*')" wire:navigate>
                        {{ __('Wallet') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="arrows-right-left" :href="route('transactions.index')" :current="request()->routeIs('transactions.*')" wire:navigate>
                        {{ __('Transaksi') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="chart-bar" :href="route('reports.sales')" :current="request()->routeIs('reports.sales')" wire:navigate>
                        {{ __('Laporan Penjualan') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Otomasi')" class="grid mt-4">
                    <flux:sidebar.item icon="chat-bubble-left-right" :href="route('help.index')" :current="request()->routeIs('help.*')" wire:navigate>
                        {{ __('Bantuan Otomatis') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

	                <flux:sidebar.group :heading="__('Sistem')" class="grid mt-4">
	                    <flux:sidebar.item icon="cog" :href="route('admin.settings')" :current="request()->routeIs('admin.settings*')" wire:navigate>
	                        {{ __('Pengaturan') }}
	                    </flux:sidebar.item>

                        @if(\Illuminate\Support\Facades\Route::has('admin.marketing-settings'))
                            <flux:sidebar.item icon="cog" :href="route('admin.marketing-settings')" :current="request()->routeIs('admin.marketing-settings*')" wire:navigate>
                                {{ __('Pengaturan Marketing') }}
                            </flux:sidebar.item>
                        @endif
	                </flux:sidebar.group>
	                @elseif(auth()->user()->isMarketing())
                <flux:sidebar.group :heading="__('Master Data')" class="grid mt-4">
                    <flux:sidebar.item icon="users" :href="route('marketing.users.index')" :current="request()->routeIs('marketing.users.*')" wire:navigate>
                        {{ __('Manajemen User') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Penjualan')" class="grid mt-4">
                    <flux:sidebar.item icon="shopping-bag" :href="route('marketing.sales.index')" :current="request()->routeIs('marketing.sales.*')" wire:navigate>
                        {{ __('Penjualan') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="chart-bar" :href="route('marketing.reports.sales')" :current="request()->routeIs('marketing.reports.sales')" wire:navigate>
                        {{ __('Laporan Penjualan') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Keuangan')" class="grid mt-4">
                    <flux:sidebar.item icon="credit-card" :href="route('marketing.wallet.index')" :current="request()->routeIs('marketing.wallet.*')" wire:navigate>
                        {{ __('Wallet') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="arrow-down-circle" :href="route('marketing.withdraw.index')" :current="request()->routeIs('marketing.withdraw.*')" wire:navigate>
                        {{ __('Withdraw') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="arrows-right-left" :href="route('marketing.transactions.index')" :current="request()->routeIs('marketing.transactions.*')" wire:navigate>
                        {{ __('Transaksi') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
                @endif
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden shrink-0 lg:block" :name="auth()->user()->name" />
        </flux:sidebar>


        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    class="h-8 w-8 overflow-hidden rounded-full border-2 border-white dark:border-zinc-700 shadow-md"
                    icon-trailing="chevron-down"
                >
                    @if(auth()->user()->avatar && file_exists(public_path(auth()->user()->avatar)))
                        <img src="{{ asset(auth()->user()->avatar) }}?t={{ time() }}"
                             alt="{{ auth()->user()->name }}"
                             class="h-full w-full rounded-full object-cover"
                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff'">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&size=128"
                             alt="{{ auth()->user()->name }}"
                             class="h-full w-full rounded-full object-cover">
                    @endif
                </flux:profile>

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                @if(auth()->user()->avatar && file_exists(public_path(auth()->user()->avatar)))
                                    <img src="{{ asset(auth()->user()->avatar) }}?t={{ time() }}"
                                         alt="{{ auth()->user()->name }}"
                                         class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-zinc-700 shadow-md"
                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff'">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&size=128"
                                         alt="{{ auth()->user()->name }}"
                                         class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-zinc-700 shadow-md">
                                @endif

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.item
                        onclick="openAdminAvatarUpload()"
                        icon="camera"
                    >
                        {{ __('Upload Foto Profil') }}
                    </flux:menu.item>

                    <flux:menu.separator />

                    <flux:menu.item
                        as="button"
                        type="button"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer"
                        data-test="logout-button"
                        onclick="openLogoutModal()"
                    >
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
        @livewireScripts
        
        <!-- Toastr JS (after jQuery) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        
        <script>
            // If the page is restored from the back/forward cache (bfcache),
            // force a reload so server-side auth/role redirects can run.
            window.addEventListener('pageshow', function (event) {
                if (event.persisted) {
                    window.location.reload();
                }
            });
        </script>

        <script>
            // Configure Toastr
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        </script>
        
        <style>
            /* Custom Toastr styling for dark mode support */
            .toast-success {
                background-color: #10b981 !important;
                color: white !important;
            }
            
            .toast-error {
                background-color: #ef4444 !important;
                color: white !important;
            }
            
            .toast-info {
                background-color: #3b82f6 !important;
                color: white !important;
            }
            
            .toast-warning {
                background-color: #f59e0b !important;
                color: white !important;
            }
            
            /* Dark mode support */
            .dark .toast-success {
                background-color: #059669 !important;
            }
            
            .dark .toast-error {
                background-color: #dc2626 !important;
            }
            
            .dark .toast-info {
                background-color: #2563eb !important;
            }
            
            .dark .toast-warning {
                background-color: #d97706 !important;
            }
            
            #toast-container > div {
                opacity: 1;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }
            
            .toast-close-button {
                color: white !important;
                opacity: 0.8;
            }
            
            .toast-close-button:hover {
                opacity: 1;
            }
        </style>

        <!-- Admin Avatar Upload Modal -->
        <div id="adminAvatarModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl dark:bg-neutral-800">
                <!-- Modal Header -->
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        {{ $avatarUploadTitle }}
                    </h3>
                    <button type="button" onclick="closeAdminAvatarUpload()" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="space-y-4">
                    <!-- Step 1: Select Image -->
                    <div id="adminSelectImageStep">
                        <label for="adminAvatarInput" class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-neutral-300 bg-neutral-50 py-12 dark:border-neutral-600 dark:bg-neutral-700/50">
                            <i data-lucide="image" class="mb-3 size-12 text-neutral-400"></i>
                            <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Klik untuk pilih gambar</p>
                            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">PNG, JPG, GIF, WEBP (Max 5MB)</p>
                        </label>
                        <input type="file" id="adminAvatarInput" accept="image/*" class="hidden" onchange="handleAdminImageSelect(event)">
                    </div>

                    <!-- Step 2: Crop Image -->
                    <div id="adminCropImageStep" class="hidden">
                        <div class="mb-4">
                            <canvas id="adminAvatarCanvas" class="max-h-64 w-full rounded-lg bg-neutral-100 dark:bg-neutral-700"></canvas>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="resetAdminCrop()" class="flex-1 rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600">
                                Batal
                            </button>
                            <button type="button" onclick="saveAdminCrop()" class="flex-1 rounded-lg bg-neutral-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-black dark:hover:bg-neutral-200">
                                Potong & Simpan
                            </button>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div id="adminLoadingStep" class="hidden py-8 text-center">
                        <div class="mb-3 flex justify-center">
                            <div class="size-8 animate-spin rounded-full border-4 border-neutral-300 border-t-neutral-900 dark:border-neutral-600 dark:border-t-white"></div>
                        </div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Mengunggah foto...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Confirmation Modal -->
        <div id="logoutModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" onclick="closeLogoutModal()">
            <div class="relative w-full max-w-md bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
                <!-- Icon Header -->
                <div class="flex justify-center pt-8 pb-4">
                    <div class="flex items-center justify-center w-16 h-16 rounded-full bg-amber-100 dark:bg-amber-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                </div>
                
                <!-- Modal Content -->
                <div class="px-6 pb-6 text-center">
                    <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-2">Logout?</h3>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">
                        Apakah Anda yakin ingin keluar dari sesi Anda saat ini?
                    </p>
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                        <p class="text-sm text-amber-700 dark:text-amber-400">
                            <strong>Catatan:</strong> Anda harus login kembali untuk mengakses sistem.
                        </p>
                    </div>
                </div>
                
                <!-- Modal Footer Buttons -->
                <div class="flex gap-3 px-6 pb-6">
                    <button onclick="closeLogoutModal()" class="flex-1 px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300 font-medium hover:bg-neutral-50 dark:hover:bg-neutral-800 transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </button>
                    <button onclick="confirmLogout()" id="confirmLogoutBtn" class="flex-1 px-4 py-2.5 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700 dark:hover:bg-red-500 transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Ya, Logout!
                    </button>
                </div>
            </div>
        </div>

        <script>
            // Use window object to avoid redeclaration errors with wire:navigate
            window.adminCropper = window.adminCropper || null;
            window.adminSelectedFile = window.adminSelectedFile || null;

            // Open admin avatar upload modal
            function openAdminAvatarUpload() {
                const modal = document.getElementById('adminAvatarModal');
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }

            // Close admin avatar upload modal
            function closeAdminAvatarUpload() {
                const modal = document.getElementById('adminAvatarModal');
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                resetAdminCrop();
            }

            // Handle admin image selection
            function handleAdminImageSelect(event) {
                const file = event.target.files[0];
                if (!file) return;

                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    toastr.error('Ukuran file terlalu besar. Maksimal 5MB');
                    return;
                }

                window.adminSelectedFile = file;
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Show crop step
                    const selectStep = document.getElementById('adminSelectImageStep');
                    const cropStep = document.getElementById('adminCropImageStep');
                    if (selectStep) selectStep.classList.add('hidden');
                    if (cropStep) cropStep.classList.remove('hidden');

                    // Initialize cropper
                    const canvas = document.getElementById('adminAvatarCanvas');
                    if (!canvas) return;
                    
                    const context = canvas.getContext('2d');
                    const img = new Image();
                    img.onload = function() {
                        canvas.width = img.width;
                        canvas.height = img.height;
                        context.drawImage(img, 0, 0);
                        
                        if (window.adminCropper) {
                            window.adminCropper.destroy();
                        }
                        if (typeof Cropper !== 'undefined') {
                            window.adminCropper = new Cropper(canvas, {
                                aspectRatio: 1,
                                viewMode: 1,
                                autoCropArea: 0.8,
                            });
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            // Reset admin crop
            function resetAdminCrop() {
                if (window.adminCropper) {
                    window.adminCropper.destroy();
                    window.adminCropper = null;
                }
                window.adminSelectedFile = null;
                const input = document.getElementById('adminAvatarInput');
                if (input) input.value = '';
                
                const selectStep = document.getElementById('adminSelectImageStep');
                const cropStep = document.getElementById('adminCropImageStep');
                const loadingStep = document.getElementById('adminLoadingStep');
                
                if (selectStep) selectStep.classList.remove('hidden');
                if (cropStep) cropStep.classList.add('hidden');
                if (loadingStep) loadingStep.classList.add('hidden');
            }

            // Save admin cropped image
            function saveAdminCrop() {
                if (!window.adminCropper) return;

                const cropStep = document.getElementById('adminCropImageStep');
                const loadingStep = document.getElementById('adminLoadingStep');
                if (cropStep) cropStep.classList.add('hidden');
                if (loadingStep) loadingStep.classList.remove('hidden');

                const croppedCanvas = window.adminCropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                });

                croppedCanvas.toBlob(function(blob) {
                    const formData = new FormData();
                    formData.append('image', blob, 'admin-avatar.jpg');

                    fetch('{{ $avatarUploadRoute }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error(data.message || 'Gagal mengunggah foto');
                            resetAdminCrop();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Terjadi kesalahan saat mengunggah foto');
                        resetAdminCrop();
                    });
                }, 'image/jpeg', 0.9);
            }

            // Use event delegation for modal closings to avoid multiple listener issues
            document.addEventListener('click', function(e) {
                const modal = document.getElementById('adminAvatarModal');
                if (e.target === modal) {
                    closeAdminAvatarUpload();
                }
                
                const logoutModal = document.getElementById('logoutModal');
                if (e.target === logoutModal) {
                    closeLogoutModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAdminAvatarUpload();
                    closeLogoutModal();
                }
            });

            // Logout Modal Functions
            function openLogoutModal() {
                const modal = document.getElementById('logoutModal');
                if (!modal) return;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeLogoutModal() {
                const modal = document.getElementById('logoutModal');
                if (!modal) return;
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }

            function confirmLogout() {
                const logoutBtn = document.getElementById('confirmLogoutBtn');
                if (!logoutBtn) return;
                
                // Show loading state
                logoutBtn.innerHTML = `
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `;
                logoutBtn.disabled = true;
                logoutBtn.classList.add('opacity-75', 'cursor-not-allowed');
                
                const form = document.getElementById('logout-form');
                if (form) form.submit();
            }
        </script>
    </body>
</html>
