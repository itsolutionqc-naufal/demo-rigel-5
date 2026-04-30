<x-layouts::app :title="__('Manajemen User')">
    @php
        $routePrefix = auth()->user()->isMarketing() ? 'marketing.' : '';
    @endphp
    <div class="container mx-auto px-4 py-6 space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Manajemen User
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Kelola semua pengguna dalam sistem
                </p>
            </div>

            <a
                href="{{ route($routePrefix.'users.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors"
            >
                <i data-lucide="plus" class="size-4"></i>
                <span>Tambah User</span>
            </a>
        </div>

	        <!-- Summary Cards -->
            @if(auth()->user()->isAdmin())
		        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
	            <!-- Card 1: Total Users -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-800 dark:to-neutral-900 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Pengguna</p>
                        <p class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">{{ $users->total() }}</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                <i data-lucide="trending-up" class="size-3"></i>
                                +{{ rand(1, 10) }}%
                            </span>
                            <span class="text-neutral-500 dark:text-neutral-400 ml-1">dari bulan lalu</span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-neutral-300 dark:bg-neutral-600 p-3">
                        <i data-lucide="users" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

	            <!-- Card 2: Admin Users -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/30 dark:to-emerald-800/30 p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Admin</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-900 dark:text-emerald-100">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                <i data-lucide="trending-up" class="size-3"></i>
                                +{{ rand(1, 5) }}%
                            </span>
                            <span class="text-neutral-500 dark:text-neutral-400 ml-1">dari bulan lalu</span>
                        </p>
                    </div>
                    <div class="rounded-lg bg-emerald-400 dark:bg-emerald-600 p-3">
                        <i data-lucide="shield-check" class="size-6 text-white"></i>
                    </div>
                </div>
            </div>

	            <!-- Card 3: Marketing Users -->
	            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-violet-100 to-violet-200 dark:from-violet-900/30 dark:to-violet-800/30 p-6 shadow-sm">
	                <div class="flex items-start justify-between">
	                    <div>
	                        <p class="text-sm font-medium text-violet-700 dark:text-violet-300">Marketing</p>
	                        <p class="mt-2 text-3xl font-bold text-violet-900 dark:text-violet-100">{{ \App\Models\User::where('role', 'marketing')->count() }}</p>
	                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
	                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
	                                <i data-lucide="trending-up" class="size-3"></i>
	                                +{{ rand(1, 8) }}%
	                            </span>
	                            <span class="text-neutral-500 dark:text-neutral-400 ml-1">dari bulan lalu</span>
	                        </p>
	                    </div>
	                    <div class="rounded-lg bg-violet-400 dark:bg-violet-600 p-3">
	                        <i data-lucide="megaphone" class="size-6 text-white"></i>
	                    </div>
	                </div>
	            </div>

	            <!-- Card 4: Regular Users -->
	            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 p-6 shadow-sm">
	                <div class="flex items-start justify-between">
	                    <div>
	                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Pengguna</p>
	                        <p class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100">{{ \App\Models\User::where('role', 'user')->count() }}</p>
	                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
	                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
	                                <i data-lucide="trending-up" class="size-3"></i>
	                                +{{ rand(1, 8) }}%
	                            </span>
	                            <span class="text-neutral-500 dark:text-neutral-400 ml-1">dari bulan lalu</span>
	                        </p>
	                    </div>
	                    <div class="rounded-lg bg-blue-400 dark:bg-blue-600 p-3">
	                        <i data-lucide="user" class="size-6 text-white"></i>
	                    </div>
	                </div>
	            </div>

	            <!-- Card 5: Newest User -->
	            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/30 dark:to-amber-800/30 p-6 shadow-sm">
	                <div class="flex items-start justify-between">
	                    <div>
	                        <p class="text-sm font-medium text-amber-700 dark:text-amber-300">Pengguna Terbaru</p>
	                        <p class="mt-2 text-3xl font-bold text-amber-900 dark:text-amber-100">{{ \App\Models\User::latest()->first()?->name ?? 'N/A' }}</p>
	                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
	                            <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400">
	                                <i data-lucide="calendar" class="size-3"></i>
	                                {{ \App\Models\User::latest()->first()?->created_at?->format('d M') ?? 'N/A' }}
	                            </span>
	                        </p>
	                    </div>
	                    <div class="rounded-lg bg-amber-400 dark:bg-amber-600 p-3">
	                        <i data-lucide="clock" class="size-6 text-white"></i>
	                    </div>
	                </div>
	            </div>
	        </div>
            @endif

        <!-- Filters Section -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
            <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center gap-2">
                    <i data-lucide="filter" class="size-5 text-neutral-400"></i>
                    <span class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Filter & Pencarian</span>
                </div>
            </div>

	            <div class="p-4">
	                <form
                        id="usersFiltersForm"
                        method="GET"
                        action="{{ route($routePrefix.'users.index') }}"
                        class="space-y-4"
                        data-users-filters
                    >
	                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
	                        <!-- Date Range Filter -->
	                        <div class="space-y-2">
	                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tanggal Registrasi</label>
	                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
	                                <div class="w-full">
	                                    <input
	                                        type="date"
	                                        name="start_date"
	                                        value="{{ request('start_date') }}"
	                                        class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
	                                        placeholder="Mulai"
	                                    />
	                                </div>
	                                <div class="w-full">
	                                    <input
	                                        type="date"
	                                        name="end_date"
	                                        value="{{ request('end_date') }}"
	                                        class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
	                                        placeholder="Akhir"
	                                    />
	                                </div>
	                            </div>
	                        </div>

                        <!-- Role Filter -->
                        @if(auth()->user()->isAdmin())
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Filter Role</label>
                                <select
                                    name="role"
                                    class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                >
                                        <option value="">Semua Role</option>
                                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="marketing" {{ request('role') === 'marketing' ? 'selected' : '' }}>Marketing</option>
                                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                                        <option value="user_marketing" {{ request('role') === 'user_marketing' ? 'selected' : '' }}>User Marketing</option>
                                    </select>
                                </div>
                        @else
                            <input type="hidden" name="role" value="user">
                        @endif

	                        <!-- Search -->
		                        <div class="space-y-2">
		                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Cari Pengguna</label>
		                            <div class="flex flex-col sm:flex-row gap-2">
		                                <input
		                                    type="search"
		                                    name="search"
		                                    placeholder="Cari nama atau email..."
		                                    value="{{ request('search') }}"
		                                    inputmode="search"
		                                    enterkeyhint="search"
		                                    autocapitalize="none"
		                                    autocorrect="off"
		                                    class="w-full sm:flex-1 px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
		                                />
		                                <button
		                                    type="button"
                                            data-users-reset
		                                    class="w-full sm:w-auto justify-center inline-flex items-center gap-1.5 px-3 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-xs transition-colors"
		                                >
		                                    <i data-lucide="rotate-ccw" class="size-4"></i>
		                                    <span>Reset</span>
		                                </button>
		                            </div>
		                        </div>
	                    </div>
	                </form>
	            </div>
	        </div>

        <!-- Users Table -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden" data-users-results>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Nama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Tanggal Dibuat
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
	                                        <div class="flex-shrink-0 h-10 w-10">
	                                            @if($user->avatar && file_exists(public_path($user->avatar)))
	                                                <img src="{{ asset($user->avatar) }}"
	                                                     alt="{{ $user->name }}"
	                                                     class="rounded-full w-10 h-10 object-cover ring-2 ring-white shadow-md"
	                                                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff'">
	                                            @else
	                                                <div class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 rounded-full w-10 h-10 flex items-center justify-center font-medium ring-2 ring-white shadow-md">
	                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
	                                                </div>
	                                            @endif
	                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-900 dark:text-white">{{ $user->email }}</div>
                                </td>
			                                <td class="px-6 py-4 whitespace-nowrap">
			                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full
			                                        @if($user->role === 'admin')
			                                            bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200
			                                        @elseif($user->role === 'marketing')
			                                            bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-200
			                                        @elseif($user->role === 'user' && ! empty($user->marketing_owner_id))
			                                            bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200
			                                        @else
			                                            bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200
			                                        @endif">
			                                        @php
	                                                    $roleLabel = ucfirst($user->role);

	                                                    if ($user->role === 'user' && ! empty($user->marketing_owner_id)) {
	                                                        $owner = $user->marketingOwner;
	                                                        $ownerLabel = $owner?->username ?: ($owner?->name ?: ($owner?->email ?: null));

	                                                        $roleLabel = $ownerLabel
	                                                            ? "User Marketing ({$ownerLabel})"
	                                                            : 'User Marketing';
	                                                    }
	                                                @endphp
			                                        {{ $roleLabel }}
			                                    </span>
			                                </td>
	                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
	                                    {{ $user->created_at->format('d M Y') }}
	                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route($routePrefix.'users.show', $user) }}"
                                            class="inline-flex items-center justify-center p-2 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 rounded-lg transition-colors"
                                            title="Lihat Detail"
                                        >
                                            <i data-lucide="eye" class="size-4"></i>
                                        </a>
	                                        <a
	                                            href="{{ route($routePrefix.'users.edit', $user) }}"
	                                            class="inline-flex items-center justify-center p-2 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 rounded-lg transition-colors"
	                                            title="Edit"
	                                        >
	                                            <i data-lucide="pencil" class="size-4"></i>
	                                        </a>
	                                        @if(auth()->user()->isAdmin())
	                                            <form
	                                                method="POST"
	                                                action="{{ route('users.destroy', $user) }}"
	                                                class="inline"
	                                                data-delete-user-form
	                                                data-user-name="{{ $user->name }}"
	                                                data-user-email="{{ $user->email }}"
	                                            >
	                                                @csrf
	                                                @method('DELETE')
	                                                <button
	                                                    type="submit"
	                                                    class="inline-flex items-center justify-center p-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-800/50 text-red-600 dark:text-red-400 rounded-lg transition-colors"
	                                                    title="Hapus"
	                                                >
	                                                    <i data-lucide="trash" class="size-4"></i>
	                                                </button>
	                                            </form>
	                                        @endif
	                                    </div>
	                                </td>
	                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="users" class="size-16 text-neutral-300 dark:text-neutral-700 mb-4"></i>
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                            Belum ada pengguna
                                        </p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                            Mulai dengan menambahkan pengguna baru
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete User Modal -->
    <div
        id="deleteUserModal"
        class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-black/50"
        aria-hidden="true"
    >
        <div
            class="w-full max-w-md rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-xl overflow-hidden"
            role="dialog"
            aria-modal="true"
            aria-labelledby="deleteUserModalTitle"
        >
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700 flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 rounded-xl bg-red-100 dark:bg-red-900/30 p-3">
                        <i data-lucide="trash-2" class="size-6 text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <h3 id="deleteUserModalTitle" class="text-lg font-semibold text-neutral-900 dark:text-white">
                            Konfirmasi Hapus
                        </h3>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                            Tindakan ini tidak bisa dibatalkan.
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    id="deleteUserModalClose"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-neutral-500 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-700/50"
                    aria-label="Tutup"
                >
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>

            <div class="p-6 space-y-2">
                <p class="text-sm text-neutral-700 dark:text-neutral-300">
                    Hapus user:
                    <span id="deleteUserModalName" class="font-semibold text-neutral-900 dark:text-white">User</span>
                </p>
                <p id="deleteUserModalEmail" class="text-xs text-neutral-500 dark:text-neutral-400"></p>
                <div class="mt-4 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 p-3">
                    <p class="text-xs text-neutral-600 dark:text-neutral-400">
                        Setelah dihapus, user tidak bisa login dan data terkait dapat ikut terhapus sesuai relasi database.
                    </p>
                </div>
            </div>

            <div class="p-6 border-t border-neutral-200 dark:border-neutral-700 flex items-center justify-end gap-2">
                <button
                    type="button"
                    id="deleteUserModalCancel"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white text-sm font-medium hover:bg-neutral-50 dark:hover:bg-neutral-700/40 transition-colors"
                >
                    <i data-lucide="arrow-left" class="size-4"></i>
                    Batal
                </button>
                <button
                    type="button"
                    id="deleteUserModalConfirm"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white text-sm font-medium transition-colors"
                >
                    <i data-lucide="trash" class="size-4"></i>
                    Ya, Hapus
                </button>
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

        // Custom delete confirmation modal
        (function () {
            const modal = document.getElementById('deleteUserModal');
            const modalName = document.getElementById('deleteUserModalName');
            const modalEmail = document.getElementById('deleteUserModalEmail');
            const cancelBtn = document.getElementById('deleteUserModalCancel');
            const confirmBtn = document.getElementById('deleteUserModalConfirm');
            const closeBtn = document.getElementById('deleteUserModalClose');

            if (!modal || !confirmBtn || !cancelBtn) {
                return;
            }

            let activeForm = null;
            let isSubmitting = false;

            function setSubmitting(state) {
                isSubmitting = state;
                confirmBtn.disabled = state;
                cancelBtn.disabled = state;
                if (closeBtn) closeBtn.disabled = state;
                confirmBtn.classList.toggle('opacity-60', state);
                confirmBtn.classList.toggle('pointer-events-none', state);
            }

            function openModal(form) {
                activeForm = form;
                const name = form?.dataset?.userName || 'User';
                const email = form?.dataset?.userEmail || '';

                if (modalName) modalName.textContent = name;
                if (modalEmail) modalEmail.textContent = email;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => confirmBtn.focus(), 0);
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                activeForm = null;
            }

            cancelBtn.addEventListener('click', closeModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);

            async function submitDelete(form) {
                if (!form || isSubmitting) return;

                const tokenInput = form.querySelector('input[name="_token"]');
                const formData = new FormData(form);

                try {
                    setSubmitting(true);

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            ...(tokenInput?.value ? { 'X-CSRF-TOKEN': tokenInput.value } : {}),
                        },
                        body: formData,
                    });

                    const payload = await response.json().catch(() => null);

                    if (!response.ok) {
                        const message = payload?.message || 'Gagal menghapus user.';
                        closeModal();
                        window.alert(message);
                        return;
                    }

                    closeModal();

                    if (window.__usersList?.reloadCurrent) {
                        window.__usersList.reloadCurrent();
                    } else {
                        window.location.reload();
                    }
                } catch (_) {
                    // Fallback to normal submit to let Laravel handle redirect + flash messages
                    form.submit();
                } finally {
                    setSubmitting(false);
                }
            }

            confirmBtn.addEventListener('click', function () {
                if (!activeForm) return;
                submitDelete(activeForm);
            });

            modal.addEventListener('click', function (e) {
                if (isSubmitting) return;
                if (e.target === modal) closeModal();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    if (isSubmitting) return;
                    closeModal();
                }
            });

            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (!(form instanceof HTMLFormElement)) return;
                if (!form.matches('form[data-delete-user-form]')) return;

                e.preventDefault();
                openModal(form);
            }, true);
        })();

        // AJAX filters + pagination for users list
        (function () {
            const form = document.querySelector('[data-users-filters]');
            let results = document.querySelector('[data-users-results]');
            const resetBtn = document.querySelector('[data-users-reset]');

            if (!form || !results) {
                return;
            }

            let debounceTimer = null;
            let activeController = null;

            function setLoading(isLoading) {
                if (!results) return;
                if (isLoading) {
                    results.setAttribute('aria-busy', 'true');
                } else {
                    results.removeAttribute('aria-busy');
                }
                results.classList.toggle('opacity-60', isLoading);
                results.classList.toggle('pointer-events-none', isLoading);
            }

            function buildQueryFromForm() {
                const data = new FormData(form);
                const params = new URLSearchParams();

                for (const [key, value] of data.entries()) {
                    const stringValue = String(value ?? '').trim();
                    if (!stringValue) continue;
                    params.set(key, stringValue);
                }

                return params.toString();
            }

            function updateHistory(url, mode) {
                try {
                    if (mode === 'push') {
                        window.history.pushState(null, '', url);
                    } else {
                        window.history.replaceState(null, '', url);
                    }
                } catch (_) {
                    // ignore history failures (e.g. sandboxed contexts)
                }
            }

            async function loadUsers(url, { historyMode = 'replace' } = {}) {
                if (activeController) {
                    activeController.abort();
                }

                activeController = new AbortController();
                setLoading(true);

                try {
                    const response = await fetch(url, {
                        signal: activeController.signal,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    });

                    if (!response.ok) {
                        throw new Error('Request failed');
                    }

                    const html = await response.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const newResults = doc.querySelector('[data-users-results]');

                    if (!newResults) {
                        throw new Error('Results container not found');
                    }

                    results.replaceWith(newResults);
                    results = newResults;

                    updateHistory(url, historyMode);
                    initLucideIcons();
                } catch (error) {
                    if (error?.name !== 'AbortError') {
                        // fallback: full reload if something goes wrong
                        window.location.href = url;
                    }
                } finally {
                    setLoading(false);
                }
            }

            function requestReload({ historyMode = 'replace' } = {}) {
                const query = buildQueryFromForm();
                const base = new URL(form.action, window.location.origin);
                const url = query ? `${base.pathname}?${query}` : base.pathname;
                loadUsers(url, { historyMode });
            }

            window.__usersList = window.__usersList || {};
            window.__usersList.reloadCurrent = function () {
                loadUsers(window.location.href, { historyMode: 'replace' });
            };

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                requestReload({ historyMode: 'replace' });
            });

            form.addEventListener('change', function (e) {
                const target = e.target;
                if (!(target instanceof HTMLElement)) return;
                if (!target.matches('input[name="start_date"], input[name="end_date"], select[name="role"]')) return;
                requestReload({ historyMode: 'replace' });
            });

            form.addEventListener('input', function (e) {
                const target = e.target;
                if (!(target instanceof HTMLElement)) return;
                if (!target.matches('input[name="search"]')) return;

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    requestReload({ historyMode: 'replace' });
                }, 300);
            });

            if (resetBtn) {
                resetBtn.addEventListener('click', function () {
                    const startDate = form.querySelector('input[name="start_date"]');
                    const endDate = form.querySelector('input[name="end_date"]');
                    const search = form.querySelector('input[name="search"]');
                    const roleSelect = form.querySelector('select[name="role"]');

                    if (startDate) startDate.value = '';
                    if (endDate) endDate.value = '';
                    if (search) search.value = '';
                    if (roleSelect) roleSelect.selectedIndex = 0;

                    clearTimeout(debounceTimer);
                    const base = new URL(form.action, window.location.origin);
                    loadUsers(base.pathname, { historyMode: 'replace' });
                });
            }

            document.addEventListener('click', function (e) {
                const link = e.target instanceof Element ? e.target.closest('a') : null;
                if (!link) return;
                if (!results || !results.contains(link)) return;

                // Handle pagination links inside results
                if (link.closest('nav[aria-label*="Pagination"], nav[aria-label*="pagination"], .pagination')) {
                    const href = link.getAttribute('href');
                    if (!href || href === '#') return;
                    e.preventDefault();
                    loadUsers(link.href, { historyMode: 'push' });
                }
            });

            window.addEventListener('popstate', function () {
                loadUsers(window.location.href, { historyMode: 'replace' });
            });
        })();
    </script>
</x-layouts::app>
