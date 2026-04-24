<?php

namespace App\Services;

use App\Models\TelegramBot;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Telegram Bot API Service
 * Documentation: https://core.telegram.org/bots/api
 */
class TelegramService
{
    protected ?string $token;
    protected string $chatId;
    protected ?string $baseUrl;

    public function __construct($token = null, $chatId = null)
    {
        // Jika tidak dikasih parameter, pakai default dari .env
        $this->token = $token
            ?? config('services.telegram.bot_token')
            ?? env('TELEGRAM_BOT_TOKEN');
        $this->token = filled($this->token) ? (string) $this->token : null;
        $this->chatId = (string) ($chatId
            ?? config('services.telegram.admin_chat_id')
            ?? env('TELEGRAM_ADMIN_CHAT_ID')
            ?? '');
        $this->baseUrl = $this->token ? "https://api.telegram.org/bot{$this->token}" : null;
    }

    protected function hasToken(): bool
    {
        return filled($this->baseUrl);
    }

    protected function missingTokenResponse(): array
    {
        Log::error('Telegram: Missing bot token (TELEGRAM_BOT_TOKEN not configured and no bot token provided)');

        return [
            'success' => false,
            'error' => 'Telegram bot token not configured',
        ];
    }

    /**
     * Create instance for specific bot.
     */
    public static function forBot($botId): ?self
    {
        $bot = TelegramBot::find($botId);
        
        if (! $bot || ! $bot->is_active || blank($bot->token)) {
            return null;
        }

        return new self($bot->token, $bot->chat_id);
    }

    /**
     * Create instance for service (uses service's bot or default).
     */
    public static function forService($service): self
    {
        $serviceChatId = $service?->telegram_chat_id ?: null;

        if ($service && $service->telegramBot && $service->telegramBot->is_active) {
            $chatId = $serviceChatId ?: $service->telegramBot->chat_id ?: config('services.telegram.admin_chat_id', env('TELEGRAM_ADMIN_CHAT_ID', ''));
            return new self($service->telegramBot->token, $chatId);
        }

        $chatId = $serviceChatId ?: config('services.telegram.admin_chat_id', env('TELEGRAM_ADMIN_CHAT_ID', ''));
        return new self(null, $chatId);
    }

    /**
     * Get current chat ID.
     */
    public function getChatId(): string
    {
        return $this->chatId;
    }

    protected function http()
    {
        // Don't throw on 4xx/5xx so we can log Telegram's "description" cleanly.
        return Http::connectTimeout(5)->timeout(10)->retry(2, 200, null, false);
    }

    protected function httpFast()
    {
        return Http::connectTimeout(2)->timeout(4);
    }

    /**
     * Send message with photo and inline keyboard buttons
     *
     * @param string $chatId Telegram chat ID
     * @param string $message Message content (supports Markdown)
     * @param string|null $photoPath Path to photo (relative to public_path)
     * @param array $buttons Button configuration [['text' => '✅ Berhasil', 'callback_data' => 'approve_123']]
     * @return array Response data
     */
    public function sendPhotoWithButtons(string $chatId, string $message, ?string $photoPath = null, array $buttons = []): array
    {
        $photoHandle = null;

        try {
            if (! $this->hasToken()) {
                return $this->missingTokenResponse();
            }

            $endpoint = "{$this->baseUrl}/sendPhoto";

            // Prepare request data
            $data = [
                'chat_id' => $chatId,
                'caption' => $message,
                'parse_mode' => 'Markdown',
            ];

            // Add photo if provided
            if ($photoPath) {
                // Remove leading slashes and get full path
                $cleanPath = ltrim($photoPath, '/');
                $fullPath = public_path($cleanPath);
                
                Log::info('Telegram: Trying to send photo', [
                    'photoPath' => $photoPath,
                    'cleanPath' => $cleanPath,
                    'fullPath' => $fullPath,
                    'exists' => file_exists($fullPath),
                ]);
                
                if (! file_exists($fullPath)) {
                    Log::warning('Telegram: Photo file not found', [
                        'path' => $fullPath,
                        'expected' => $cleanPath,
                    ]);
                    $photoPath = null;
                } else {
                    $photoHandle = fopen($fullPath, 'r');
                }
            }

            // Add buttons if provided
            if (!empty($buttons)) {
                $data['reply_markup'] = json_encode([
                    'inline_keyboard' => $this->formatButtons($buttons),
                ]);
            }

            Log::info('Telegram: Sending photo request', [
                'endpoint' => $endpoint,
                'hasPhoto' => (bool) $photoHandle,
                'hasButtons' => !empty($buttons),
            ]);

            $request = $this->http()->asMultipart();
            if ($photoHandle) {
                $request = $request->attach('photo', $photoHandle, basename((string) $photoPath));
            }

            $response = $request->post($endpoint, $data);

            $result = $response->json();

            Log::info('Telegram: Photo send response', [
                'ok' => $result['ok'] ?? false,
                'status' => $response->status(),
                'description' => $result['description'] ?? 'N/A',
            ]);

            if ($response->successful() && isset($result['ok']) && $result['ok'] === true) {
                Log::info('Telegram: Photo with buttons sent successfully', [
                    'chat_id' => $chatId,
                    'message_id' => $result['result']['message_id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'message_id' => $result['result']['message_id'] ?? null,
                    'response' => $result,
                ];
            }

            Log::error('Telegram: Failed to send photo', [
                'chat_id' => $chatId,
                'error' => $result['description'] ?? 'Unknown error',
                'status' => $response->status(),
                'full_response' => $result,
            ]);

            // Fallback: send text message (so admin still gets notif + buttons)
            $fallback = $this->sendMessageWithButtons($chatId, $message, $buttons);
            if (! empty($fallback['success'])) {
                return [
                    'success' => true,
                    'message_id' => $fallback['message_id'] ?? null,
                    'fallback_used' => true,
                    'error' => $result['description'] ?? 'Failed to send photo',
                    'response' => [
                        'send_photo' => $result,
                        'send_message' => $fallback['response'] ?? null,
                    ],
                ];
            }

            return [
                'success' => false,
                'fallback_used' => true,
                'error' => $result['description'] ?? ($fallback['error'] ?? 'Failed to send'),
                'response' => [
                    'send_photo' => $result,
                    'send_message' => $fallback['response'] ?? null,
                ],
            ];

        } catch (\Exception $e) {
            Log::error('Telegram: Exception occurred', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Fallback for unexpected exceptions too.
            $fallback = $this->sendMessageWithButtons($chatId, $message, $buttons);
            if (! empty($fallback['success'])) {
                return [
                    'success' => true,
                    'message_id' => $fallback['message_id'] ?? null,
                    'fallback_used' => true,
                    'error' => $e->getMessage(),
                    'response' => [
                        'send_message' => $fallback['response'] ?? null,
                    ],
                ];
            }

            return [
                'success' => false,
                'fallback_used' => true,
                'error' => $e->getMessage(),
            ];
        } finally {
            if (is_resource($photoHandle)) {
                fclose($photoHandle);
            }
        }
    }

    /**
     * Send message with inline keyboard buttons
     *
     * @param string $chatId Telegram chat ID
     * @param string $message Message content (supports Markdown)
     * @param array $buttons Button configuration [['text' => '✅ Berhasil', 'callback_data' => 'approve_123']]
     * @return array Response data
     */
    public function sendMessageWithButtons(string $chatId, string $message, array $buttons = []): array
    {
        try {
            if (! $this->hasToken()) {
                return $this->missingTokenResponse();
            }

            $response = $this->http()->post("{$this->baseUrl}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $this->formatButtons($buttons),
                ]),
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['ok']) && $result['ok'] === true) {
                Log::info('Telegram: Message with buttons sent successfully', [
                    'chat_id' => $chatId,
                    'message_id' => $result['result']['message_id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'message_id' => $result['result']['message_id'] ?? null,
                    'response' => $result,
                ];
            }

            Log::error('Telegram: Failed to send message', [
                'chat_id' => $chatId,
                'error' => $result['description'] ?? 'Unknown error',
            ]);

            return [
                'success' => false,
                'error' => $result['description'] ?? 'Failed to send',
                'response' => $result,
            ];

        } catch (\Exception $e) {
            Log::error('Telegram: Exception occurred', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send simple text message
     */
    public function sendMessage(string $chatId, string $message): array
    {
        try {
            if (! $this->hasToken()) {
                return $this->missingTokenResponse();
            }

            $response = $this->http()->post("{$this->baseUrl}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['ok']) && $result['ok'] === true) {
                Log::info('Telegram: Message sent successfully', [
                    'chat_id' => $chatId,
                ]);

                return [
                    'success' => true,
                    'message_id' => $result['result']['message_id'] ?? null,
                    'response' => $result,
                ];
            }

            return [
                'success' => false,
                'error' => $result['description'] ?? 'Failed to send',
                'response' => $result,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send message to admin
     */
    public function sendToAdmin(string $message, array $buttons = []): array
    {
        if (empty($this->chatId)) {
            return [
                'success' => false,
                'error' => 'Admin chat ID not configured',
            ];
        }

        if (!empty($buttons)) {
            return $this->sendMessageWithButtons($this->chatId, $message, $buttons);
        }

        return $this->sendMessage($this->chatId, $message);
    }

    /**
     * Format buttons for inline keyboard
     */
    protected function formatButtons(array $buttons): array
    {
        $keyboard = [];

        foreach ($buttons as $button) {
            $keyboard[] = [
                [
                    'text' => $button['text'],
                    'callback_data' => $button['callback_data'],
                ],
            ];
        }

        return $keyboard;
    }

    /**
     * Get bot info
     */
    public function getBotInfo(): array
    {
        try {
            if (! $this->hasToken()) {
                return [
                    'ok' => false,
                    'description' => 'Telegram bot token not configured',
                ];
            }

            $response = $this->http()->get("{$this->baseUrl}/getMe");
            return $response->json();
        } catch (\Exception $e) {
            return [
                'ok' => false,
                'description' => $e->getMessage(),
            ];
        }
    }

    /**
     * Set webhook for receiving updates
     */
    public function setWebhook(string $url): array
    {
        try {
            if (! $this->hasToken()) {
                return [
                    'ok' => false,
                    'description' => 'Telegram bot token not configured',
                ];
            }

            $payload = [
                'url' => $url,
                'allowed_updates' => ['message', 'callback_query'],
            ];

            $secret = (string) (config('services.telegram.webhook_secret') ?? env('TELEGRAM_WEBHOOK_SECRET') ?? '');
            if (filled($secret)) {
                // Telegram will send this back in `X-Telegram-Bot-Api-Secret-Token`.
                $payload['secret_token'] = $secret;
            }

            $response = $this->http()->post("{$this->baseUrl}/setWebhook", $payload);

            return $response->json();
        } catch (\Exception $e) {
            return [
                'ok' => false,
                'description' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get webhook info
     */
    public function getWebhookInfo(): array
    {
        try {
            if (! $this->hasToken()) {
                return [
                    'ok' => false,
                    'description' => 'Telegram bot token not configured',
                ];
            }

            $response = $this->http()->get("{$this->baseUrl}/getWebhookInfo");
            return $response->json();
        } catch (\Exception $e) {
            return [
                'ok' => false,
                'description' => $e->getMessage(),
            ];
        }
    }

    /**
     * Answer callback query (when button is clicked)
     */
    public function answerCallbackQuery(string $callbackQueryId, string $message = null): array
    {
        try {
            if (! $this->hasToken()) {
                return [
                    'ok' => false,
                    'description' => 'Telegram bot token not configured',
                ];
            }

            $data = [
                'callback_query_id' => $callbackQueryId,
                'show_alert' => !empty($message),
            ];

            if ($message) {
                $data['text'] = $message;
            }

            $response = $this->httpFast()->post("{$this->baseUrl}/answerCallbackQuery", $data);
            return $response->json();
        } catch (\Exception $e) {
            return [
                'ok' => false,
                'description' => $e->getMessage(),
            ];
        }
    }

    /**
     * Edit message text (for confirmation after button click)
     */
    public function editMessageText(int $chatId, int $messageId, string $text): array
    {
        try {
            if (! $this->hasToken()) {
                return [
                    'ok' => false,
                    'description' => 'Telegram bot token not configured',
                ];
            }

            $response = $this->httpFast()->post("{$this->baseUrl}/editMessageText", [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $text,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [],
                ]),
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return [
                'ok' => false,
                'description' => $e->getMessage(),
            ];
        }
    }

    /**
     * Edit message caption (for photo messages with caption)
     */
    public function editMessageCaption(int $chatId, int $messageId, string $caption): array
    {
        try {
            if (! $this->hasToken()) {
                return [
                    'ok' => false,
                    'description' => 'Telegram bot token not configured',
                ];
            }

            $response = $this->httpFast()->post("{$this->baseUrl}/editMessageCaption", [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [],
                ]),
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return [
                'ok' => false,
                'description' => $e->getMessage(),
            ];
        }
    }
}
