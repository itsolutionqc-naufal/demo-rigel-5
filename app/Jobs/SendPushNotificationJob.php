<?php

namespace App\Jobs;

use App\Services\PushNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array<int, int>
     */
    public $backoff = [10, 30, 60];

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    public function __construct(
        public int $userId,
        public string $title,
        public string $body,
        public array $data = [],
    ) {
    }

    public function handle(): void
    {
        $credentialsPath = env('FIREBASE_CREDENTIALS') ?: env('GOOGLE_APPLICATION_CREDENTIALS');
        if ($credentialsPath && !str_starts_with($credentialsPath, DIRECTORY_SEPARATOR)) {
            $credentialsPath = base_path($credentialsPath);
        }

        if (!$credentialsPath || !is_file($credentialsPath)) {
            Log::info('FCM skipped: credentials not configured', [
                'user_id' => $this->userId,
                'attempt' => $this->attempts(),
            ]);
            return;
        }

        Log::info('Processing Firebase push notification job', [
            'user_id' => $this->userId,
            'title' => $this->title,
            'attempt' => $this->attempts(),
            'max_tries' => $this->tries,
        ]);

        $result = app(PushNotificationService::class)->sendToUser(
            userId: $this->userId,
            title: $this->title,
            body: $this->body,
            data: $this->data,
        );

        if ($result['fcm_sent'] ?? false) {
            Log::info('Firebase push notification sent successfully', [
                'user_id' => $this->userId,
                'title' => $this->title,
                'attempt' => $this->attempts(),
            ]);
        } else {
            $errors = $result['errors'] ?? [];
            Log::warning('Firebase push notification failed', [
                'user_id' => $this->userId,
                'title' => $this->title,
                'attempt' => $this->attempts(),
                'errors' => $errors,
            ]);

            // If there are errors and we haven't exhausted retries, throw exception to trigger retry
            if (!empty($errors) && $this->attempts() < $this->tries) {
                throw new \RuntimeException('Firebase push notification failed: ' . implode('; ', $errors));
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Firebase push notification job failed after all retries', [
            'user_id' => $this->userId,
            'title' => $this->title,
            'body' => $this->body,
            'attempts' => $this->attempts(),
            'max_tries' => $this->tries,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        // TODO: Optionally send alert to admin or store in failed_jobs table for manual review
        // For now, we just log the failure
    }
}
