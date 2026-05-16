<x-layouts::app :title="__('Detail User')">
    @php
        $routePrefix = auth()->user()->isMarketing() ? 'marketing.' : '';
    @endphp
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
	        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
	            <div>
	                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
	                    Detail User
	                </h1>
	                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
	                    Melihat informasi untuk "{{ $user->name }}"
	                </p>
	            </div>

	            <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
	                <a 
	                    href="{{ route($routePrefix.'users.index') }}" 
	                    class="w-full sm:w-auto justify-center inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors"
	                >
	                    <i data-lucide="arrow-left" class="size-4"></i>
	                    <span>Kembali</span>
	                </a>
	                <a 
	                    href="{{ route($routePrefix.'users.edit', $user) }}" 
	                    class="w-full sm:w-auto justify-center inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors"
	                >
	                    <i data-lucide="pencil" class="size-4"></i>
	                    <span>Edit</span>
	                </a>
	            </div>
	        </div>

        <!-- User Details Card -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
            <div class="p-6">
                <!-- User Avatar and Info -->
	                <div class="flex flex-col sm:flex-row sm:items-center items-center gap-4 sm:gap-6 mb-8 text-center sm:text-left">
	                    <div class="flex-shrink-0">
	                        @if(!empty($user->avatar))
	                            <img
	                                src="{{ asset($user->avatar) }}"
	                                alt="{{ $user->name }}"
	                                class="rounded-full w-20 h-20 sm:w-24 sm:h-24 object-cover border-4 border-white dark:border-white shadow-sm"
	                            />
	                        @else
	                            <div class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 rounded-full w-20 h-20 sm:w-24 sm:h-24 flex items-center justify-center font-bold text-xl sm:text-2xl border-4 border-white dark:border-white shadow-sm">
	                                {{ strtoupper(substr($user->name, 0, 1)) }}
	                            </div>
	                        @endif
	                    </div>
	                    <div>
	                        <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $user->name }}</h2>
	                        <div class="mt-2 flex items-center gap-4 text-sm text-neutral-500 dark:text-neutral-400">
	                            <span>{{ $user->email }}</span>
	                        </div>
	                    </div>
	                </div>

                <!-- User Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Name -->
                    <div>
                        <h3 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nama Lengkap</h3>
                        <p class="text-neutral-900 dark:text-white">{{ $user->name }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <h3 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Alamat Email</h3>
                        <p class="text-neutral-900 dark:text-white">{{ $user->email }}</p>
                    </div>

                    <!-- Role -->
                    <div>
                        <h3 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Role</h3>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($user->role === 'admin') 
                                bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200
                            @elseif($user->role === 'marketing')
                                bg-violet-100 dark:bg-violet-900/30 text-violet-800 dark:text-violet-200
                            @else
                                bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200
                            @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <!-- Created At -->
                    <div>
                        <h3 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Tanggal Dibuat</h3>
                        <p class="text-neutral-900 dark:text-white">{{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <!-- Updated At -->
                    <div>
                        <h3 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Terakhir Diupdate</h3>
                        <p class="text-neutral-900 dark:text-white">{{ $user->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <!-- Actions -->
	                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 pt-6 border-t border-neutral-200 dark:border-neutral-700">
	                    @if(auth()->user()->isAdmin())
	                        <form 
	                            method="POST" 
	                            action="{{ route('users.destroy', $user) }}" 
	                            class="w-full sm:w-auto"
	                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')"
	                        >
	                            @csrf
	                            @method('DELETE')
	                            <button
	                                type="submit"
	                                class="w-full sm:w-auto justify-center inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white rounded-lg font-medium text-sm transition-colors"
	                            >
	                                <i data-lucide="trash" class="size-4"></i>
	                                <span>Hapus User</span>
	                            </button>
	                        </form>
	                    @endif
	                </div>
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
    </script>
</x-layouts::app>
