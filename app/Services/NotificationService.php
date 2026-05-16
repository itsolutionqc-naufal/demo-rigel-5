<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Service;
use App\Models\User;
use App\Support\TelegramMessageFormatter;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected static array $lastDispatchResults = [];

    /**
     * Create a notification for a user.
     */
    public function createNotification(User $user, string $title, string $message, string $type = 'info', array $data = [])
    {
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
        ]);
    }

    /**
     * Create notification for transaction status change.
     * 
     * This method creates in-app notifications synchronously and dispatches
     * Firebase push notifications to job queue for asynchronous processing.
     */
    public function notifyTransactionStatusChange($transaction, string $oldStatus, string $newStatus): array
    {
        $dispatchResult = [
            'transaction_id' => (int) $transaction->id,
            'new_status' => (string) $newStatus,
            'fcm_sent' => false,
            'fcm_error' => null,
            'jobs_dispatched' => 0,
        ];

        $user = $transaction->user;

        if (($transaction->transaction_type ?? null) === 'withdrawal') {
            if ($user && in_array($newStatus, ['pending', 'success', 'failed'], true)) {
                $this->notifyCommissionWithdrawal($user, $transaction->amount, $newStatus);
            }
        } else {
            $code = (string) ($transaction->transaction_code ?: ('TRX-' . $transaction->id));
            $amountFormatted = 'Rp ' . number_format((float) ($transaction->amount ?? 0), 0, ',', '.');
            $commissionFormatted = 'Rp ' . number_format((float) ($transaction->commission_amount ?? 0), 0, ',', '.');

            $statusMessages = [
                'process' => [
                    'title' => '⏳ Transaksi Diproses',
                    'message' => "Transaksi {$code} sedang diproses oleh admin.\n\nNominal: {$amountFormatted}",
                    'type' => 'info',
                ],
                'success' => [
                    'title' => '✅ Transaksi Berhasil!',
                    'message' => "Selamat! Transaksi {$code} telah disetujui.\n\nNominal: {$amountFormatted}\nKomisi: {$commissionFormatted}\n\nKomisi telah ditambahkan ke saldo Anda!",
                    'type' => 'success',
                ],
                'failed' => [
                    'title' => '❌ Transaksi Ditolak',
                    'message' => "Mohon maaf, transaksi {$code} ditolak.\n\nNominal: {$amountFormatted}\n\nSilakan hubungi admin untuk informasi lebih lanjut.",
                    'type' => 'error',
                ],
            ];

            if ($user && isset($statusMessages[$newStatus])) {
                $notification = $statusMessages[$newStatus];

                // Create in-app notification synchronously
                $this->createNotification(
                    $user,
                    $notification['title'],
                    $notification['message'],
                    $notification['type'],
                    [
                        'transaction_id' => $transaction->id,
                        'transaction_code' => $transaction->transaction_code,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'amount' => $transaction->amount,
                        'commission_amount' => $transaction->commission_amount,
                    ]
                );

                // Dispatch Firebase push notification to job queue for success/failed status
                if (in_array($newStatus, ['success', 'failed'], true)) {
                    $jobsDispatched = $this->dispatchPushNotification(
                        userId: (int) $user->id,
                        title: $notification['title'],
                        body: $notification['message'],
                        data: [
                            'type' => $newStatus,
                            'transaction_id' => (string) $transaction->id,
                            'transaction_code' => (string) ($transaction->transaction_code ?? ''),
                            'new_status' => $newStatus,
                        ]
                    );
                    $dispatchResult['jobs_dispatched'] += $jobsDispatched;
                    $dispatchResult['fcm_sent'] = true; // Job dispatched successfully
                }
            }
        }

        if (in_array($newStatus, ['success', 'failed'], true)) {
            $adminJobsDispatched = $this->notifyAdminsTransactionFinalStatus($transaction, $oldStatus, $newStatus);
            $dispatchResult['jobs_dispatched'] += $adminJobsDispatched;
            $this->notifyTelegramTransactionFinalStatus($transaction, $newStatus);
        }

        self::$lastDispatchResults[(int) $transaction->id] = $dispatchResult;

        return $dispatchResult;
    }

    /**
     * Dispatch Firebase push notification to job queue.
     * 
     * @return int Number of jobs dispatched
     */
    protected function dispatchPushNotification(int $userId, string $title, string $body, array $data = []): int
    {
        try {
            \App\Jobs\SendPushNotificationJob::dispatch($userId, $title, $body, $data);
            
            Log::info('Firebase push notification job dispatched', [
                'user_id' => $userId,
                'title' => $title,
            ]);
            
            return 1;
        } catch (\Throwable $e) {
            Log::error('Failed to dispatch Firebase push notification job', [
                'user_id' => $userId,
                'title' => $title,
                'error' => $e->getMessage(),
            ]);
            
            return 0;
        }
    }

    protected function notifyAdminsTransactionFinalStatus($transaction, string $oldStatus, string $newStatus): int
    {
        $jobsDispatched = 0;
        $code = (string) ($transaction->transaction_code ?: ('#' . $transaction->id));
        
        // Get admin who processed/rejected the transaction
        $admin = $transaction->admin;
        $adminName = $admin ? $admin->name : 'Admin';
        
        // Get user who created the transaction
        $user = $transaction->user;
        $userName = $user ? $user->name : 'Unknown';
        
        // Format amount
        $amountFormatted = 'Rp ' . number_format((float) ($transaction->amount ?? 0), 0, ',', '.');
        $commissionFormatted = 'Rp ' . number_format((float) ($transaction->commission_amount ?? 0), 0, ',', '.');

        if ($newStatus === 'success') {
            $title = "✅ Transaksi Disetujui";
            $message = "{$adminName} telah menyetujui transaksi {$code} dari {$userName}.\n\n";
            $message .= "Nominal: {$amountFormatted}\n";
            $message .= "Komisi: {$commissionFormatted}";
        } else {
            $title = "❌ Transaksi Ditolak";
            $message = "{$adminName} telah menolak transaksi {$code} dari {$userName}.\n\n";
            $message .= "Nominal: {$amountFormatted}";
        }

        $admins = User::query()
            ->where('role', User::ROLE_ADMIN)
            ->get();

        foreach ($admins as $admin) {
            // Create in-app notification synchronously
            $this->createNotification(
                $admin,
                $title,
                $message,
                $newStatus === 'success' ? 'success' : 'error',
                [
                    'type' => 'admin_transaction_status',
                    'transaction_type' => $newStatus,
                    'transaction_id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'amount' => $transaction->amount,
                    'processed_by' => $adminName,
                ]
            );

            // Dispatch Firebase push notification to job queue
            $jobsDispatched += $this->dispatchPushNotification(
                userId: (int) $admin->id,
                title: $title,
                body: $message,
                data: [
                    'type' => $newStatus === 'success' ? 'success' : 'failed',
                    'transaction_type' => 'admin_notification',
                    'transaction_id' => (string) $transaction->id,
                    'transaction_code' => (string) ($transaction->transaction_code ?? ''),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'processed_by' => $adminName,
                ]
            );
        }

        return $jobsDispatched;
    }

    public static function takeLastDispatchResult(int $transactionId): ?array
    {
        if (! array_key_exists($transactionId, self::$lastDispatchResults)) {
            return null;
        }

        $result = self::$lastDispatchResults[$transactionId];
        unset(self::$lastDispatchResults[$transactionId]);

        return $result;
    }

    protected function notifyTelegramTransactionFinalStatus($transaction, string $newStatus): void
    {
        try {
            $service = null;
            $serviceName = (string) ($transaction->service_name ?? '');
            if ($serviceName !== '') {
                $service = Service::query()
                    ->with('telegramBot')
                    ->where('name', $serviceName)
                    ->first();
            }

            $telegram = TelegramService::forService($service);
            $statusLabel = $newStatus === 'success' ? 'BERHASIL' : 'GAGAL';
            $title = $newStatus === 'success'
                ? 'UPDATE STATUS TRANSAKSI (SUKSES)'
                : 'UPDATE STATUS TRANSAKSI (GAGAL)';

            $message = TelegramMessageFormatter::heading($title);
            $message .= TelegramMessageFormatter::bullet('Kode', (string) ($transaction->transaction_code ?? ('TRX-' . $transaction->id)));
            $message .= TelegramMessageFormatter::bullet('Layanan', (string) ($transaction->service_name ?? '-'));
            $message .= TelegramMessageFormatter::bullet('Nominal', 'Rp ' . number_format((float) $transaction->amount, 0, ',', '.'), false);
            $message .= TelegramMessageFormatter::divider();
            $message .= TelegramMessageFormatter::bullet('Status', $statusLabel, false);
            $message .= TelegramMessageFormatter::bullet('Waktu', now()->format('d/m/Y H:i:s'));

            $result = $telegram->sendMessage($telegram->getChatId(), $message);
            if (! ($result['success'] ?? false)) {
                Log::warning('Telegram status final notification failed', [
                    'transaction_id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'status' => $newStatus,
                    'error' => $result['error'] ?? 'unknown',
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Telegram status final notification exception', [
                'transaction_id' => $transaction->id,
                'transaction_code' => $transaction->transaction_code,
                'status' => $newStatus,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Create notification for commission withdrawal.
     */
    public function notifyCommissionWithdrawal($user, $amount, $status = 'pending')
    {
        $statusMessages = [
            'pending' => [
                'title' => 'Permintaan Penarikan Komisi',
                'message' => "Permintaan penarikan komisi sebesar Rp " . number_format($amount, 0, ',', '.') . " sedang diproses.",
                'type' => 'info'
            ],
            'success' => [
                'title' => 'Penarikan Komisi Berhasil',
                'message' => "Penarikan komisi sebesar Rp " . number_format($amount, 0, ',', '.') . " telah berhasil diproses.",
                'type' => 'success'
            ],
            'failed' => [
                'title' => 'Penarikan Komisi Ditolak',
                'message' => "Penarikan komisi sebesar Rp " . number_format($amount, 0, ',', '.') . " ditolak. Silakan hubungi admin.",
                'type' => 'error'
            ]
        ];

        if (isset($statusMessages[$status])) {
            $notification = $statusMessages[$status];
            
            $this->createNotification(
                $user,
                $notification['title'],
                $notification['message'],
                $notification['type'],
                [
                    'type' => 'withdrawal',
                    'amount' => $amount,
                    'status' => $status
                ]
            );
        }
    }

    /**
     * Create welcome notification for new users.
     */
    public function notifyWelcomeUser($user)
    {
        $this->createNotification(
            $user,
            'Selamat Datang di Rigel Agency!',
            'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!',
            'success',
            ['type' => 'welcome']
        );
    }

    /**
     * Create notification for new article published.
     */
    public function notifyNewArticle($article)
    {
        // Notify all users about new article
        $users = User::where('role', '!=', 'admin')->get();
        
        foreach ($users as $user) {
            $this->createNotification(
                $user,
                'Artikel Baru Tersedia',
                "Artikel baru '{$article->title}' telah dipublikasikan. Baca sekarang untuk mendapatkan informasi terbaru!",
                'info',
                [
                    'type' => 'article',
                    'article_id' => $article->id,
                    'article_title' => $article->title
                ]
            );
        }
    }

    /**
     * Create notification for system maintenance.
     */
    public function notifySystemMaintenance($title, $message, $scheduledAt = null)
    {
        $users = User::all();
        
        foreach ($users as $user) {
            $this->createNotification(
                $user,
                $title,
                $message,
                'warning',
                [
                    'type' => 'maintenance',
                    'scheduled_at' => $scheduledAt
                ]
            );
        }
    }

    /**
     * Get unread notifications count for a user.
     */
    public function getUnreadCount(User $user)
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead(User $user)
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
