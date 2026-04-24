<x-layouts::app :title="__('Edit User')">
    @php
        $routePrefix = auth()->user()->isMarketing() ? 'marketing.' : '';
    @endphp
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Edit User
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Memperbarui informasi untuk "{{ $user->name }}"
                </p>
            </div>

            <a 
                href="{{ route($routePrefix.'users.index') }}" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors"
            >
                <i data-lucide="arrow-left" class="size-4"></i>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Edit Form -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
            <form method="POST" action="{{ route($routePrefix.'users.update', $user) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Form Header -->
                <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        Informasi User
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        Lengkapi formulir di bawah untuk memperbarui user
                    </p>
                </div>

                <!-- Form Body -->
                <div class="p-6 space-y-6">
                    <!-- Avatar Upload -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Foto Profil
                        </label>
                        <div class="flex items-center gap-4">
	                            <div class="relative">
	                                @if($user->avatar && file_exists(public_path($user->avatar)))
	                                    <img id="avatarPreview" src="{{ asset($user->avatar) }}" 
	                                         alt="Avatar" 
	                                         class="h-24 w-24 rounded-full object-cover ring-4 ring-white shadow-lg">
	                                @else
	                                    <div id="avatarPreview" class="h-24 w-24 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-800 dark:text-indigo-200 font-semibold text-2xl ring-4 ring-white shadow-lg">
	                                        {{ strtoupper(substr($user->name, 0, 1)) }}
	                                    </div>
	                                @endif
	                                <button type="button" onclick="document.getElementById('avatarInput').click()" 
	                                        class="absolute bottom-0 right-0 rounded-full bg-neutral-900 p-2 text-white shadow-md hover:bg-neutral-700 dark:bg-white dark:text-black dark:hover:bg-neutral-200">
                                    <i data-lucide="camera" class="size-4"></i>
                                </button>
                            </div>
                            <div class="flex-1">
                                <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden" onchange="handleAvatarPreview(event)">
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">Klik ikon kamera untuk upload foto profil</p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-500 mt-1">PNG, JPG, GIF, WEBP (Max 5MB)</p>
                            </div>
                        </div>
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <flux:input
                            name="name"
                            label="Nama Lengkap"
                            placeholder="Masukkan nama lengkap"
                            :value="old('name', $user->name)"
                            required
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <flux:input
                            name="email"
                            type="email"
                            label="Alamat Email"
                            placeholder="contoh@email.com"
                            :value="old('email', $user->email)"
                            required
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password (optional) -->
                    <div>
                        <flux:input
                            name="password"
                            type="password"
                            label="Password Baru (Opsional)"
                            placeholder="Masukkan password baru jika ingin mengganti"
                        />
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            Biarkan kosong jika tidak ingin mengganti password
                        </p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <flux:input
                            name="password_confirmation"
                            type="password"
                            label="Konfirmasi Password Baru"
                            placeholder="Ulangi password baru"
                        />
                    </div>

                    <!-- Role -->
                    @if(auth()->user()->isAdmin())
                        <div>
                            <flux:select name="role" label="Role Pengguna" :value="old('role', $user->role)" required>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="marketing" {{ old('role', $user->role) === 'marketing' ? 'selected' : '' }}>Marketing</option>
                            </flux:select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <flux:select
                                name="marketing_owner_id"
                                label="Marketing Owner (Opsional)"
                                :value="old('marketing_owner_id', $user->marketing_owner_id)"
                            >
                                <option value="">— Tidak ada —</option>
                                @foreach(($marketingOwners ?? collect()) as $marketingOwner)
                                    <option value="{{ $marketingOwner->id }}" {{ (string) old('marketing_owner_id', $user->marketing_owner_id) === (string) $marketingOwner->id ? 'selected' : '' }}>
                                        {{ $marketingOwner->name }} ({{ $marketingOwner->email }})
                                    </option>
                                @endforeach
                            </flux:select>
                            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                Hanya berlaku untuk user role "User". Jika role diubah jadi Admin/Marketing maka ownership akan dikosongkan.
                            </p>
                            @error('marketing_owner_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="role" value="user">
                    @endif
                </div>

                <!-- Form Footer -->
                <div class="p-6 border-t border-neutral-200 dark:border-neutral-700">
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 sm:space-x-reverse gap-3">
                        <a 
                            href="{{ route($routePrefix.'users.index') }}"
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
                            <span>Perbarui User</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Handle avatar preview
	        function handleAvatarPreview(event) {
	            const file = event.target.files[0];
	            if (!file) return;

            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                toastr.error('Ukuran file terlalu besar. Maksimal 5MB');
                event.target.value = '';
                return;
            }

	            const reader = new FileReader();
	            reader.onload = function(e) {
	                const preview = document.getElementById('avatarPreview');
	                preview.outerHTML = `<img id="avatarPreview" src="${e.target.result}" alt="Avatar" class="h-24 w-24 rounded-full object-cover ring-4 ring-white shadow-lg">`;
	            };
	            reader.readAsDataURL(file);
	        }

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
    </script>
</x-layouts::app>
