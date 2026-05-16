<x-layouts::app :title="__('User yang Dihapus')">
    <div class="container mx-auto px-4 py-6 space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">User yang Dihapus</h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Kelola user yang telah dihapus</p>
            </div>
            <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-sm transition-colors">
                <i data-lucide="arrow-left" class="size-4"></i>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Filters -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
            <div class="p-4">
                <form method="GET" action="{{ route('users.deleted') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="search" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}" class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">Cari</button>
                        <a href="{{ route('users.deleted') }}" class="px-4 py-2 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm font-medium">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Dihapus</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">{{ $user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-900 dark:text-white">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full bg-neutral-100 text-neutral-800 dark:bg-neutral-700 dark:text-neutral-200">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $user->sale_transactions_count ?? 0 }} transaksi
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $user->deleted_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            type="button"
                                            data-restore-btn
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            data-user-email="{{ $user->email }}"
                                            data-user-transactions="{{ $user->sale_transactions_count ?? 0 }}"
                                            data-user-amount="{{ $user->saleTransactions->first()->total_amount ?? 0 }}"
                                            data-user-deleted="{{ $user->deleted_at->format('d M Y H:i') }}"
                                            class="inline-flex items-center justify-center p-2 bg-emerald-100 dark:bg-emerald-900/30 hover:bg-emerald-200 dark:hover:bg-emerald-800/50 text-emerald-600 dark:text-emerald-400 rounded-lg transition-colors" 
                                            title="Restore">
                                            <i data-lucide="rotate-ccw" class="size-4"></i>
                                        </button>
                                        <form method="POST" action="{{ route('users.force-destroy', $user->id) }}" class="inline" onsubmit="return confirm('Hapus permanen? Data tidak bisa dikembalikan!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center p-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-800/50 text-red-600 dark:text-red-400 rounded-lg transition-colors" title="Hapus Permanen">
                                                <i data-lucide="trash-2" class="size-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="inbox" class="size-16 text-neutral-300 dark:text-neutral-700 mb-4"></i>
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">Tidak ada user yang dihapus</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Restore User Modal -->
    <div id="restoreUserModal" class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-black/50" aria-hidden="true">
        <div class="w-full max-w-md rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-xl overflow-hidden" role="dialog" aria-modal="true">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700 flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 p-3">
                        <i data-lucide="rotate-ccw" class="size-6 text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Konfirmasi Restore User</h3>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Masukkan email untuk konfirmasi</p>
                    </div>
                </div>
                <button type="button" id="restoreModalClose" class="inline-flex items-center justify-center rounded-lg p-2 text-neutral-500 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-700/50">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <!-- User Info -->
                <div class="rounded-xl border border-emerald-200 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 p-4 space-y-3">
                    <p class="text-sm font-semibold text-emerald-900 dark:text-emerald-100" id="restoreUserName">User Name</p>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-xs text-emerald-700 dark:text-emerald-300">
                            <i data-lucide="mail" class="size-3.5 flex-shrink-0"></i>
                            <span class="font-medium">Email:</span>
                            <span id="restoreUserEmail" class="font-normal">user@email.com</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-emerald-700 dark:text-emerald-300">
                            <i data-lucide="shopping-cart" class="size-3.5 flex-shrink-0"></i>
                            <span class="font-medium">Total Transaksi:</span>
                            <span id="restoreUserTransactions" class="font-normal">0</span>
                            <span class="font-normal">transaksi</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-emerald-700 dark:text-emerald-300">
                            <i data-lucide="dollar-sign" class="size-3.5 flex-shrink-0"></i>
                            <span class="font-medium">Total Penjualan:</span>
                            <span id="restoreUserAmount" class="font-normal">Rp 0</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-emerald-700 dark:text-emerald-300">
                            <i data-lucide="trash-2" class="size-3.5 flex-shrink-0"></i>
                            <span class="font-medium">Dihapus:</span>
                            <span id="restoreUserDeleted" class="font-normal">-</span>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form id="restoreUserForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="restoreUserId" name="user_id">
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            <div class="flex items-center gap-2">
                                <i data-lucide="mail" class="size-4"></i>
                                <span>Masukkan Email yang Mau Di-restore</span>
                            </div>
                        </label>
                        <div class="relative">
                            <input type="email" name="email" id="restoreEmail" required placeholder="Ketik email user yang akan di-restore" class="w-full px-3 py-2 pr-10 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all" />
                            <div id="emailIcon" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                                <i data-lucide="check" class="size-5 text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            <div class="flex items-center gap-2">
                                <i data-lucide="mail-check" class="size-4"></i>
                                <span>Konfirmasi Email (Ketik Manual)</span>
                            </div>
                        </label>
                        <div class="relative">
                            <input type="email" name="email_confirmation" id="restoreEmailConfirm" required placeholder="Ketik ulang email untuk konfirmasi" class="w-full px-3 py-2 pr-10 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-neutral-500 transition-all" />
                            <div id="emailConfirmIcon" class="hidden absolute right-3 top-1/2 -translate-y-1/2"></div>
                        </div>
                        <p class="mt-1.5 text-xs text-neutral-500 dark:text-neutral-400 flex items-start gap-1.5">
                            <i data-lucide="info" class="size-3.5 flex-shrink-0 mt-0.5"></i>
                            <span>Ketik manual email yang sama dengan field di atas untuk konfirmasi</span>
                        </p>
                    </div>

                    <div id="restoreError" class="hidden rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-3">
                        <div class="flex items-start gap-2">
                            <i data-lucide="alert-circle" class="size-4 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5"></i>
                            <p class="text-sm text-red-600 dark:text-red-400"></p>
                        </div>
                    </div>
                </form>
            </div>

            <div class="p-6 border-t border-neutral-200 dark:border-neutral-700 flex items-center justify-end gap-2">
                <button type="button" id="restoreModalCancel" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white text-sm font-medium hover:bg-neutral-50 dark:hover:bg-neutral-700/40 transition-colors">
                    <i data-lucide="x" class="size-4"></i>
                    Batal
                </button>
                <button type="button" id="restoreModalConfirm" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white text-sm font-medium transition-colors">
                    <i data-lucide="rotate-ccw" class="size-4"></i>
                    Restore User
                </button>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* SweetAlert2 Dark Mode Custom Styles */
        .swal-dark {
            border: 1px solid #404040 !important;
        }
        .swal-dark .swal2-timer-progress-bar {
            background: #10b981 !important;
        }
    </style>

    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Restore User Modal
        (function() {
            const modal = document.getElementById('restoreUserModal');
            const form = document.getElementById('restoreUserForm');
            const closeBtn = document.getElementById('restoreModalClose');
            const cancelBtn = document.getElementById('restoreModalCancel');
            const confirmBtn = document.getElementById('restoreModalConfirm');
            const errorDiv = document.getElementById('restoreError');
            
            const emailInput = document.getElementById('restoreEmail');
            const emailConfirmInput = document.getElementById('restoreEmailConfirm');
            const emailIcon = document.getElementById('emailIcon');
            const emailConfirmIcon = document.getElementById('emailConfirmIcon');

            let currentUserId = null;
            let currentUserEmail = null;
            let currentUserName = null;

            // Real-time validation for email confirmation
            function validateEmailConfirm() {
                const email = emailInput.value.trim();
                const emailConfirm = emailConfirmInput.value.trim();

                if (!emailConfirm) {
                    // Empty state
                    emailConfirmInput.classList.remove('ring-2', 'ring-emerald-500', 'ring-red-500', 'border-emerald-500', 'border-red-500', 'focus:ring-emerald-500', 'focus:ring-red-500');
                    emailConfirmInput.classList.add('focus:ring-neutral-500');
                    emailConfirmIcon.classList.add('hidden');
                    emailConfirmIcon.innerHTML = '';
                    return;
                }

                if (email === emailConfirm && email === currentUserEmail) {
                    // Valid - match with user email
                    emailConfirmInput.classList.remove('ring-red-500', 'border-red-500', 'focus:ring-neutral-500', 'focus:ring-red-500');
                    emailConfirmInput.classList.add('ring-2', 'ring-emerald-500', 'border-emerald-500', 'focus:ring-emerald-500');
                    emailConfirmIcon.classList.remove('hidden');
                    emailConfirmIcon.innerHTML = '<i data-lucide="check" class="size-5 text-emerald-600 dark:text-emerald-400"></i>';
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                } else {
                    // Invalid
                    emailConfirmInput.classList.remove('ring-emerald-500', 'border-emerald-500', 'focus:ring-neutral-500', 'focus:ring-emerald-500');
                    emailConfirmInput.classList.add('ring-2', 'ring-red-500', 'border-red-500', 'focus:ring-red-500');
                    emailConfirmIcon.classList.remove('hidden');
                    emailConfirmIcon.innerHTML = '<i data-lucide="x" class="size-5 text-red-600 dark:text-red-400"></i>';
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            }

            // Real-time validation for first email
            function validateEmail() {
                const email = emailInput.value.trim();

                if (!email) {
                    emailInput.classList.remove('ring-2', 'ring-emerald-500', 'border-emerald-500');
                    emailInput.classList.add('focus:ring-emerald-500');
                    emailIcon.classList.add('hidden');
                    return;
                }

                if (email === currentUserEmail) {
                    // Valid
                    emailInput.classList.add('ring-2', 'ring-emerald-500', 'border-emerald-500', 'focus:ring-emerald-500');
                    emailIcon.classList.remove('hidden');
                } else {
                    // Invalid
                    emailInput.classList.remove('ring-2', 'ring-emerald-500', 'border-emerald-500');
                    emailInput.classList.add('focus:ring-emerald-500');
                    emailIcon.classList.add('hidden');
                }

                // Re-validate confirmation when first email changes
                validateEmailConfirm();
            }

            // Event listeners for real-time validation
            emailInput.addEventListener('input', validateEmail);
            emailInput.addEventListener('blur', validateEmail);
            emailConfirmInput.addEventListener('input', validateEmailConfirm);
            emailConfirmInput.addEventListener('blur', validateEmailConfirm);

            function showError(message) {
                errorDiv.classList.remove('hidden');
                errorDiv.querySelector('p').textContent = message;
                setTimeout(() => {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }, 10);
            }

            function hideError() {
                errorDiv.classList.add('hidden');
            }

            function openModal(btn) {
                const userId = btn.dataset.userId;
                const userName = btn.dataset.userName;
                const userEmail = btn.dataset.userEmail;
                const userTransactions = btn.dataset.userTransactions;
                const userAmount = btn.dataset.userAmount;
                const userDeleted = btn.dataset.userDeleted;

                currentUserId = userId;
                currentUserEmail = userEmail;
                currentUserName = userName;

                document.getElementById('restoreUserId').value = userId;
                document.getElementById('restoreUserName').textContent = userName;
                document.getElementById('restoreUserEmail').textContent = userEmail;
                document.getElementById('restoreUserTransactions').textContent = userTransactions;
                document.getElementById('restoreUserAmount').textContent = 'Rp ' + parseInt(userAmount).toLocaleString('id-ID');
                document.getElementById('restoreUserDeleted').textContent = userDeleted;

                form.reset();
                hideError();
                
                // Reset validation states
                emailInput.classList.remove('ring-2', 'ring-emerald-500', 'ring-red-500', 'border-emerald-500', 'border-red-500');
                emailConfirmInput.classList.remove('ring-2', 'ring-emerald-500', 'ring-red-500', 'border-emerald-500', 'border-red-500');
                emailIcon.classList.add('hidden');
                emailConfirmIcon.classList.add('hidden');

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
                setTimeout(() => {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }, 50);
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                currentUserId = null;
                currentUserEmail = null;
                currentUserName = null;
            }

            // Event listeners
            document.querySelectorAll('[data-restore-btn]').forEach(btn => {
                btn.addEventListener('click', () => openModal(btn));
            });

            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);

            confirmBtn.addEventListener('click', async function() {
                hideError();

                const email = emailInput.value.trim();
                const emailConfirm = emailConfirmInput.value.trim();

                if (!email || !emailConfirm) {
                    showError('Email dan konfirmasi email harus diisi!');
                    return;
                }

                if (email !== emailConfirm) {
                    showError('Email dan konfirmasi email tidak sama!');
                    return;
                }

                if (email !== currentUserEmail) {
                    showError('Email tidak sesuai dengan email user yang akan di-restore!');
                    return;
                }

                confirmBtn.disabled = true;
                confirmBtn.innerHTML = '<i data-lucide="loader-2" class="size-4 animate-spin"></i> Memproses...';

                try {
                    const formData = new FormData(form);
                    const response = await fetch(`/users/${currentUserId}/restore`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        closeModal();
                        
                        // Detect dark mode
                        const isDarkMode = document.documentElement.classList.contains('dark') || 
                                         window.matchMedia('(prefers-color-scheme: dark)').matches;
                        
                        // Show success alert with redirect
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            html: `User <strong>${currentUserName}</strong><br><small>(${currentUserEmail})</small><br>berhasil di-restore!`,
                            showConfirmButton: true,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#10b981',
                            timer: 3000,
                            timerProgressBar: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            background: isDarkMode ? '#262626' : '#ffffff',
                            color: isDarkMode ? '#ffffff' : '#000000',
                            customClass: {
                                popup: isDarkMode ? 'swal-dark' : '',
                                title: isDarkMode ? 'text-white' : '',
                                htmlContainer: isDarkMode ? 'text-neutral-300' : ''
                            }
                        }).then(() => {
                            window.location.href = '/users';
                        });
                    } else {
                        showError(data.message || 'Gagal restore user!');
                    }
                } catch (error) {
                    showError('Terjadi kesalahan. Silakan coba lagi.');
                } finally {
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i data-lucide="rotate-ccw" class="size-4"></i> Restore User';
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        })();
    </script>
</x-layouts::app>
