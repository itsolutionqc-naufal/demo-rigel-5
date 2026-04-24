<x-layouts::mobile title="Status Transaksi">
    <div class="flex flex-col flex-1 p-4">
        {{-- Loading State --}}
        <div id="loadingState" class="flex flex-col items-center justify-center h-full">
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-amber-400 mb-4"></div>
            <p class="text-neutral-600 dark:text-neutral-400 text-sm">Memuat status transaksi...</p>
        </div>

        {{-- Transaction Details --}}
        <div id="transactionDetails" class="hidden space-y-4">
            {{-- Header --}}
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-neutral-900 dark:text-white mb-2">Status Transaksi</h2>
                <div id="statusBadge" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold">
                    <span id="statusIcon"></span>
                    <span id="statusText"></span>
                </div>
            </div>

            {{-- Transaction Info Card --}}
            <div class="bg-white dark:bg-neutral-900 rounded-xl p-5 shadow-sm border border-neutral-200 dark:border-neutral-800">
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-neutral-500 dark:text-neutral-400">Kode Transaksi</span>
                        <span class="text-sm font-semibold text-neutral-900 dark:text-white" id="txCode"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-neutral-500 dark:text-neutral-400">Layanan</span>
                        <span class="text-sm font-semibold text-neutral-900 dark:text-white" id="txService"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-neutral-500 dark:text-neutral-400">ID Pengguna</span>
                        <span class="text-sm font-semibold text-neutral-900 dark:text-white" id="txUserId"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-neutral-500 dark:text-neutral-400">Nickname</span>
                        <span class="text-sm font-semibold text-neutral-900 dark:text-white" id="txNickname"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-neutral-500 dark:text-neutral-400">Nominal</span>
                        <span class="text-sm font-semibold text-amber-500" id="txAmount"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-neutral-500 dark:text-neutral-400">Pembayaran</span>
                        <span class="text-sm font-semibold text-neutral-900 dark:text-white" id="txPayment"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-neutral-500 dark:text-neutral-400">No. Rekening</span>
                        <span class="text-sm font-mono text-neutral-900 dark:text-white" id="txAccount"></span>
                    </div>
                </div>
            </div>

            {{-- Status Timeline --}}
            <div class="bg-neutral-50 dark:bg-neutral-950 rounded-xl p-4 border border-neutral-200 dark:border-neutral-800">
                <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-3">Timeline Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-neutral-900 dark:text-white">Transaksi Dibuat</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400" id="createdAt"></p>
                        </div>
                    </div>
                    <div id="approvedTimeline" class="flex items-center gap-3 opacity-50">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-neutral-300 dark:bg-neutral-700 flex items-center justify-center">
                            <i data-lucide="clock" class="w-4 h-4 text-neutral-500"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Disetujui Admin</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400" id="approvedAt">Menunggu...</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Message --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5"></i>
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        Halaman ini akan otomatis update ketika admin memproses transaksi Anda melalui Telegram.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    <div id="successModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-neutral-900 rounded-2xl p-6 max-w-sm w-full shadow-2xl transform transition-all scale-100">
            <div class="text-center mb-4">
                <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="check" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-1">Transaksi Berhasil!</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Transaksi Anda telah disetujui</p>
            </div>

            <div class="bg-neutral-50 dark:bg-neutral-950 rounded-xl p-4 mb-4 space-y-2">
                <div class="flex justify-between">
                    <span class="text-xs text-neutral-500 dark:text-neutral-400">Kode Transaksi</span>
                    <span class="text-xs font-semibold text-neutral-900 dark:text-white" id="modalTxCode"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-neutral-500 dark:text-neutral-400">User ID</span>
                    <span class="text-xs font-semibold text-neutral-900 dark:text-white" id="modalUserId"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-neutral-500 dark:text-neutral-400">Nickname</span>
                    <span class="text-xs font-semibold text-neutral-900 dark:text-white" id="modalNickname"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-neutral-500 dark:text-neutral-400">Nominal</span>
                    <span class="text-xs font-semibold text-amber-500" id="modalAmount"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-neutral-500 dark:text-neutral-400">Status</span>
                    <span class="text-xs font-semibold text-green-500">APPROVED ✅</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-neutral-500 dark:text-neutral-400">Disetujui</span>
                    <span class="text-xs font-semibold text-neutral-900 dark:text-white" id="modalApprovedAt"></span>
                </div>
            </div>

            <button onclick="redirectToHistory()" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-xl transition">
                Lihat Riwayat
            </button>
        </div>
    </div>

    {{-- Failed Modal --}}
    <div id="failedModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-neutral-900 rounded-2xl p-6 max-w-sm w-full shadow-2xl transform transition-all scale-100">
            <div class="text-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-500 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="x" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-1">Transaksi Ditolak</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Admin menolak transaksi Anda</p>
            </div>

            <div class="bg-neutral-50 dark:bg-neutral-950 rounded-xl p-4 mb-4 space-y-2">
                <div class="flex justify-between">
                    <span class="text-xs text-neutral-500 dark:text-neutral-400">Kode Transaksi</span>
                    <span class="text-xs font-semibold text-neutral-900 dark:text-white" id="failedModalTxCode"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-neutral-500 dark:text-neutral-400">User ID</span>
                    <span class="text-xs font-semibold text-neutral-900 dark:text-white" id="failedModalUserId"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-neutral-500 dark:text-neutral-400">Nickname</span>
                    <span class="text-xs font-semibold text-neutral-900 dark:text-white" id="failedModalNickname"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-neutral-500 dark:text-neutral-400">Nominal</span>
                    <span class="text-xs font-semibold text-amber-500" id="failedModalAmount"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-neutral-500 dark:text-neutral-400">Status</span>
                    <span class="text-xs font-semibold text-red-500">REJECTED ❌</span>
                </div>
            </div>

            <button onclick="redirectToHistory()" class="w-full bg-neutral-700 hover:bg-neutral-600 text-white font-semibold py-3 px-4 rounded-xl transition">
                Kembali
            </button>
        </div>
    </div>

    <script>
        let transactionCode = "{{ $transactionCode ?? '' }}";
        let pollingInterval;
        let lastChecked = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            if (transactionCode) {
                startPolling();
            } else {
                showError('Kode transaksi tidak ditemukan');
            }
        });

        // Start polling every 3 seconds
        function startPolling() {
            checkStatus(); // Check immediately
            pollingInterval = setInterval(checkStatus, 3000);
        }

        // Stop polling
        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
        }

        // Check transaction status
        async function checkStatus() {
            try {
                const url = `/app/check-transaction/${transactionCode}?last_checked=${lastChecked || ''}`;
                console.log('Checking status:', url);
                
                const response = await fetch(url);
                console.log('Response status:', response.status);
                
                const data = await response.json();
                console.log('Response data:', data);

                if (data.success) {
                    lastChecked = new Date().toISOString();
                    updateUI(data.transaction);

                    // Show modal if status changed
                    if (data.statusChanged) {
                        console.log('Status changed!', data.status);
                        if (data.status === 'success') {
                            showSuccessModal(data.transaction);
                        } else if (data.status === 'failed') {
                            showFailedModal(data.transaction);
                        }
                    }
                } else {
                    console.error('Check status failed:', data);
                }
            } catch (error) {
                console.error('Error checking status:', error);
            }
        }

        // Update UI with transaction data
        function updateUI(tx) {
            const loadingState = document.getElementById('loadingState');
            const transactionDetails = document.getElementById('transactionDetails');
            
            if (loadingState) loadingState.classList.add('hidden');
            if (transactionDetails) transactionDetails.classList.remove('hidden');

            // Update status badge
            const statusBadge = document.getElementById('statusBadge');
            const statusIcon = document.getElementById('statusIcon');
            const statusText = document.getElementById('statusText');

            if (statusBadge && statusIcon && statusText) {
                if (tx.status === 'success') {
                    statusBadge.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
                    statusIcon.innerHTML = '✅';
                    statusText.textContent = tx.status_label;
                    
                    // Update approved timeline
                    const approvedTimeline = document.getElementById('approvedTimeline');
                    const approvedAt = document.getElementById('approvedAt');
                    if (approvedTimeline) approvedTimeline.classList.remove('opacity-50');
                    if (approvedAt) {
                        approvedAt.textContent = tx.confirmed_at || 'Sudah disetujui';
                        approvedAt.classList.remove('text-neutral-500');
                        approvedAt.classList.add('text-green-500', 'font-semibold');
                    }
                } else if (tx.status === 'failed') {
                    statusBadge.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
                    statusIcon.innerHTML = '❌';
                    statusText.textContent = tx.status_label;
                } else {
                    statusBadge.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
                    statusIcon.innerHTML = '⏳';
                    statusText.textContent = tx.status_label;
                }
            }

            // Update transaction details (with null checks)
            const elements = {
                'txCode': tx.transaction_code,
                'txService': tx.service_name,
                'txUserId': tx.user_id_input,
                'txNickname': tx.nickname,
                'txAmount': tx.formatted_amount,
                'txPayment': tx.payment_method,
                'txAccount': tx.payment_number,
            };

            for (const [id, value] of Object.entries(elements)) {
                const el = document.getElementById(id);
                if (el) el.textContent = value;
            }
        }

        // Show success modal
        function showSuccessModal(tx) {
            stopPolling();
            
            document.getElementById('modalTxCode').textContent = tx.transaction_code;
            document.getElementById('modalUserId').textContent = tx.user_id_input;
            document.getElementById('modalNickname').textContent = tx.nickname;
            document.getElementById('modalAmount').textContent = tx.formatted_amount;
            document.getElementById('modalApprovedAt').textContent = tx.confirmed_at || '-';
            
            document.getElementById('successModal').classList.remove('hidden');
            
            // Play notification sound
            playNotificationSound();
            
            // Auto redirect to history after 3 seconds
            setTimeout(() => {
                redirectToHistory();
            }, 3000);
        }

        // Show failed modal
        function showFailedModal(tx) {
            stopPolling();
            
            document.getElementById('failedModalTxCode').textContent = tx.transaction_code;
            document.getElementById('failedModalUserId').textContent = tx.user_id_input;
            document.getElementById('failedModalNickname').textContent = tx.nickname;
            document.getElementById('failedModalAmount').textContent = tx.formatted_amount;
            
            document.getElementById('failedModal').classList.remove('hidden');
            
            // Play notification sound
            playNotificationSound();
        }

        // Redirect to history
        function redirectToHistory() {
            window.location.href = '/app/history';
        }

        // Play notification sound
        function playNotificationSound() {
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleQQAKZXZ8NOmdgIAn7/xqo+JFgCk0fKxkIwXAKTQ8q+PihYApM/yrY+K FgCkz/KsjooWAKTN8qyOihYApMzyrI6KFgCky/KsjooWAKTK8qyOihYApMnyrI6KFgCkx/KsjooWAKTG8qyOihYApMXyrI6KFgCkxPKsjooWAKTD8qyOihYApMLyrI6KFgCkwvKsjooWAKTB8qyOihYApL/y rI6KFgCkvfKsjooWAKS78qyOihYApLnyrI6KFgCktvKsjooWAKS18qyOihYApLPyrI6KFgCksvKsjooWAKSx8qyOihYApK/yrI6KFgCkrvKsjooWAKSt8qyOihYApKzyrI6KFgCkq/KsjooWAKSp8qyOihYA pKfyrI6KFgCkp/KsjooWAKSl8qyOihYApKPyrI6KFgCkovKsjooWAKSh8qyOihYApJ/yrI6KFgCkm/KsjooWAKSZ8qyOihYApJfyrI6KFgCklvKsjooWAKSV8qyOihYApJPy rI6KFgCkkfKsjooWAKSP8qyOihYApI3yrI6KFgCki/KsjooWAKSL8qyOihYApInyrI6KFgCkiPKsjooWAKSH8qyOihYAhHk=');
            audio.play().catch(e => console.log('Audio play failed:', e));
        }

        // Show error
        function showError(message) {
            document.getElementById('loadingState').innerHTML = `
                <div class="text-center">
                    <i data-lucide="alert-circle" class="w-16 h-16 text-red-500 mx-auto mb-4"></i>
                    <p class="text-red-500 text-sm">${message}</p>
                    <button onclick="window.location.href='/app/history'" class="mt-4 px-4 py-2 bg-neutral-900 dark:bg-white text-white dark:text-neutral-900 rounded-lg text-sm font-semibold">
                        Kembali ke Riwayat
                    </button>
                </div>
            `;
            lucide.createIcons();
        }
    </script>
</x-layouts::mobile>
