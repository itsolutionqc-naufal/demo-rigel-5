<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
            Notifikasi
            @if($unreadCount > 0)
                <span class="inline-flex items-center justify-center w-6 h-6 ml-2 text-xs font-bold text-white bg-red-500 rounded-full">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        </h2>
        @if($unreadCount > 0)
            <button onclick="markAllAsRead()" class="text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                Tandai semua dibaca
            </button>
        @else
            <span class="text-xs text-neutral-500 dark:text-neutral-400">
                Semua sudah dibaca
            </span>
        @endif
    </div>

    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div 
                class="flex gap-4 rounded-xl border {{ $notification->read_at ? 'border-neutral-100 bg-white dark:border-neutral-800 dark:bg-neutral-900' : 'border-blue-100 bg-blue-50/50 dark:border-blue-900/30 dark:bg-blue-900/10' }} p-4 cursor-pointer hover:shadow-md transition-shadow"
                data-notification-id="{{ $notification->id }}"
                onclick="markAsRead({{ $notification->id }})"
            >
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $notification->type === 'success' ? 'text-emerald-600 bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400' : ($notification->type === 'warning' ? 'text-amber-600 bg-amber-100 dark:bg-amber-500/10 dark:text-amber-400' : ($notification->type === 'error' ? 'text-red-600 bg-red-100 dark:bg-red-500/10 dark:text-red-400' : 'text-blue-600 bg-blue-100 dark:bg-blue-500/10 dark:text-blue-400')) }}">
                    @if($notification->type === 'success')
                        <i data-lucide="check-circle" class="size-5"></i>
                    @elseif($notification->type === 'warning')
                        <i data-lucide="alert-triangle" class="size-5"></i>
                    @elseif($notification->type === 'error')
                        <i data-lucide="x-circle" class="size-5"></i>
                    @else
                        <i data-lucide="bell" class="size-5"></i>
                    @endif
                </div>
                <div class="flex-1 space-y-1">
                    <div class="flex items-start justify-between">
                        <h3 class="font-semibold text-neutral-900 dark:text-white">
                            {{ $notification->title }}
                            @if(!$notification->read_at)
                                <span class="inline-block w-2 h-2 bg-blue-500 rounded-full ml-2"></span>
                            @endif
                        </h3>
                        <span class="text-xs text-neutral-500 dark:text-neutral-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                        {{ $notification->message }}
                    </p>
                    
                    @if($notification->data && isset($notification->data['type']))
                        <div class="flex items-center gap-2 mt-2">
                            @if($notification->data['type'] === 'transaction' && isset($notification->data['amount']))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                    <i data-lucide="banknote" class="size-3 mr-1"></i>
                                    Rp {{ number_format($notification->data['amount'], 0, ',', '.') }}
                                </span>
                            @endif
                            
                            @if($notification->data['type'] === 'withdrawal' && isset($notification->data['amount']))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                    <i data-lucide="wallet" class="size-3 mr-1"></i>
                                    Penarikan Rp {{ number_format($notification->data['amount'], 0, ',', '.') }}
                                </span>
                            @endif
                            
                            @if($notification->data['type'] === 'article')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400">
                                    <i data-lucide="book-open" class="size-3 mr-1"></i>
                                    Artikel
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-12">
                <i data-lucide="bell-off" class="size-12 text-neutral-300 dark:text-neutral-700 mb-4"></i>
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-1">
                    Tidak Ada Notifikasi
                </h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 text-center">
                    Anda belum memiliki notifikasi apapun.<br>
                    Notifikasi akan muncul ketika ada aktivitas baru di akun Anda.
                </p>
                <div class="mt-6 p-4 bg-neutral-50 dark:bg-neutral-800/50 rounded-lg">
                    <h4 class="text-sm font-medium text-neutral-900 dark:text-white mb-2">
                        Jenis Notifikasi:
                    </h4>
                    <ul class="text-xs text-neutral-600 dark:text-neutral-400 space-y-1">
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="size-3 text-green-500"></i>
                            Status transaksi berubah
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="wallet" class="size-3 text-blue-500"></i>
                            Penarikan komisi
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="book-open" class="size-3 text-purple-500"></i>
                            Artikel baru dipublikasikan
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="alert-triangle" class="size-3 text-amber-500"></i>
                            Pengumuman sistem
                        </li>
                    </ul>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="flex justify-center">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<script>
    // Mark all notifications as read
    async function markAllAsRead() {
        try {
            const response = await fetch('{{ route('notifications.mark.all.read') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                // Reload the page to update the notifications
                location.reload();
            } else {
                alert('Gagal menandai semua notifikasi sebagai dibaca');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menandai notifikasi');
        }
    }

    // Mark a single notification as read
    async function markAsRead(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                // Update the UI to show the notification as read
                const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
                if (notificationElement) {
                    notificationElement.classList.remove('border-blue-100', 'bg-blue-50/50', 'dark:border-blue-900/30', 'dark:bg-blue-900/10');
                    notificationElement.classList.add('border-neutral-100', 'bg-white', 'dark:border-neutral-800', 'dark:bg-neutral-900');
                }
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>
