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
            Log::info('FCM skipped: credentials not configured');
            return;
        }

        app(PushNotificationService::class)->sendToUser(
            userId: $this->userId,
            title: $this->title,
            body: $this->body,
            data: $this->data,
        );
    }
}
