<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Jobs\SendPushNotificationJob;

class NotificationService
{
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
     */
    public function notifyTransactionStatusChange($transaction, string $oldStatus, string $newStatus)
    {
        $user = $transaction->user;

        if (($transaction->transaction_type ?? null) === 'withdrawal') {
            if (in_array($newStatus, ['pending', 'success', 'failed'], true)) {
                $this->notifyCommissionWithdrawal($user, $transaction->amount, $newStatus);
            }

            return;
        }
        
        $statusMessages = [
            'process' => [
                'title' => 'Transaksi Sedang Diproses',
                'message' => "Transaksi #{$transaction->id} sedang diproses oleh admin.",
                'type' => 'info'
            ],
            'success' => [
                'title' => 'Transaksi Berhasil',
                'message' => "Transaksi #{$transaction->id} telah berhasil disetujui. Komisi sebesar Rp " . number_format($transaction->commission_amount, 0, ',', '.') . " telah ditambahkan ke saldo Anda.",
                'type' => 'success'
            ],
            'failed' => [
                'title' => 'Transaksi Ditolak',
                'message' => "Transaksi #{$transaction->id} ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.",
                'type' => 'error'
            ]
        ];

        if (isset($statusMessages[$newStatus])) {
            $notification = $statusMessages[$newStatus];
            
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
                    'commission_amount' => $transaction->commission_amount
                ]
            );

            if (in_array($newStatus, ['success', 'failed'], true)) {
                SendPushNotificationJob::dispatch(
                    userId: (int) $user->id,
                    title: $notification['title'],
                    body: $notification['message'],
                    data: [
                        'transaction_id' => (string) $transaction->id,
                        'transaction_code' => (string) ($transaction->transaction_code ?? ''),
                        'new_status' => $newStatus,
                    ],
                );
            }
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
