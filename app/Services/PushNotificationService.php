<?php

namespace App\Services;

use App\Models\DeviceToken;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Exception\Messaging\InvalidRegistrationToken;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    public function __construct(private Messaging $messaging)
    {
    }

    public function sendToUser(int $userId, string $title, string $body, array $data = []): void
    {
        $tokens = DeviceToken::query()
            ->where('user_id', $userId)
            ->pluck('token')
            ->all();

        if (empty($tokens)) {
            return;
        }

        foreach ($tokens as $token) {
            try {
                $message = CloudMessage::new()
                    ->withTarget('token', $token)
                    ->withNotification(FcmNotification::create($title, $body))
                    ->withData(array_map('strval', $data));

                $this->messaging->send($message);
            } catch (InvalidRegistrationToken|NotFound $e) {
                DeviceToken::where('token_hash', hash('sha256', $token))->delete();
            } catch (InvalidMessage $e) {
                Log::warning('FCM invalid message', ['errors' => $e->errors()]);
            } catch (\Throwable $e) {
                Log::error('FCM send failed', ['error' => $e->getMessage()]);
            }
        }
    }
}
