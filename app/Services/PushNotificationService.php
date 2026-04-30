<?php

namespace App\Services;

use App\Models\DeviceToken;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Exception\Messaging\InvalidRegistrationToken;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    public function __construct(private Messaging $messaging)
    {
    }

    public function sendToUser(int $userId, string $title, string $body, array $data = []): array
    {
        $tokens = DeviceToken::query()
            ->where('user_id', $userId)
            ->pluck('token')
            ->all();

        $result = [
            'user_id' => $userId,
            'attempted' => count($tokens),
            'sent' => 0,
            'failed' => 0,
            'fcm_sent' => false,
            'errors' => [],
        ];

        if (empty($tokens)) {
            $result['errors'][] = 'No device token found';
            return $result;
        }

        foreach ($tokens as $token) {
            try {
                $message = CloudMessage::new()
                    ->withTarget('token', $token)
                    ->withNotification(FcmNotification::create($title, $body))
                    ->withAndroidConfig(AndroidConfig::fromArray([
                        'priority' => 'high',
                        'notification' => [
                            'channel_id' => 'rigel_alerts',
                            'sound' => 'default',
                        ],
                    ]))
                    ->withData(array_map('strval', $data));

                $sendResult = $this->messaging->send($message);
                Log::info('FCM send success', [
                    'user_id' => $userId,
                    'token_hash' => hash('sha256', $token),
                    'result' => $sendResult,
                ]);
                $result['sent']++;
            } catch (InvalidRegistrationToken|NotFound $e) {
                DeviceToken::where('token_hash', hash('sha256', $token))->delete();
                $result['failed']++;
                $result['errors'][] = 'Invalid or not found registration token';
            } catch (InvalidMessage $e) {
                Log::warning('FCM invalid message', ['errors' => $e->errors()]);
                $result['failed']++;
                $result['errors'][] = 'Invalid message payload';
            } catch (\Throwable $e) {
                Log::error('FCM send failed', ['error' => $e->getMessage()]);
                $result['failed']++;
                $result['errors'][] = $e->getMessage();
            }
        }

        $result['fcm_sent'] = $result['sent'] > 0;

        return $result;
    }
}
