<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WaNugasinService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $adminNumber;

    public function __construct()
    {
        $this->apiKey = config('services.wa_nugasin.api_key', env('WA_NUGASIN_API_KEY'));
        $this->baseUrl = config('services.wa_nugasin.base_url', env('WA_NUGASIN_BASE_URL', 'https://wa.nugasin.com'));
        $this->adminNumber = env('WA_NUGASIN_ADMIN_NUMBER', '');
    }

    /**
     * Send WhatsApp message
     * Format API Wa Nugasin: https://wa.nugasin.com/webhook?key=API_KEY&no=PHONE&text=MESSAGE
     *
     * @param string $to Phone number (e.g., '6281234567890')
     * @param string $message Message content
     * @param string|null $filePath Optional file path for attachment
     * @return array Response data
     */
    public function sendMessage(string $to, string $message, ?string $filePath = null): array
    {
        try {
            // Try multiple API formats
            $formats = [
                // Format 1: GET with query params
                [
                    'method' => 'GET',
                    'url' => $this->baseUrl . '/webhook?key=' . urlencode($this->apiKey) . '&no=' . urlencode($to) . '&text=' . urlencode($message),
                ],
                // Format 2: POST with form data
                [
                    'method' => 'POST',
                    'url' => $this->baseUrl . '/webhook',
                    'data' => [
                        'key' => $this->apiKey,
                        'no' => $to,
                        'text' => $message,
                    ],
                ],
                // Format 3: POST with JSON
                [
                    'method' => 'POST',
                    'url' => $this->baseUrl . '/api/send-message',
                    'data' => [
                        'api_key' => $this->apiKey,
                        'phone' => $to,
                        'message' => $message,
                    ],
                ],
            ];

            foreach ($formats as $format) {
                $result = $this->trySendFormat($format, $to);
                
                if ($result['success']) {
                    return $result;
                }
            }

            // All formats failed
            Log::error('Wa Nugasin: All API formats failed', [
                'to' => $to,
                'api_key' => substr($this->apiKey, 0, 4) . '...',
            ]);

            return [
                'success' => false,
                'error' => 'All API formats failed. Please check API key and Wa Nugasin account.',
            ];

        } catch (\Exception $e) {
            Log::error('Wa Nugasin: Exception occurred', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Try sending with specific format
     */
    protected function trySendFormat(array $format, string $to): array
    {
        try {
            if ($format['method'] === 'GET') {
                $response = Http::get($format['url']);
            } else {
                $response = Http::asForm()->post($format['url'], $format['data']);
            }

            $result = $response->body();
            $responseData = json_decode($result, true);

            // Check for success indicators
            if ($response->successful() && 
                (str_contains(strtolower($result), 'success') || 
                 str_contains(strtolower($result), 'sent') ||
                 str_contains(strtolower($result), 'ok') ||
                 (isset($responseData['status']) && in_array(strtolower($responseData['status']), ['success', 'sent', 'ok'])))) {
                
                Log::info('Wa Nugasin: Message sent successfully', [
                    'to' => $to,
                    'format' => $format['method'],
                    'response' => $result,
                ]);

                return [
                    'success' => true,
                    'message_id' => $responseData['message_id'] ?? $this->generateMessageId(),
                    'response' => $result,
                ];
            }

            return [
                'success' => false,
                'error' => $result,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate unique message ID for tracking
     * Used when API doesn't return message_id
     */
    protected function generateMessageId(): string
    {
        return 'WA-' . uniqid() . '-' . time();
    }

    /**
     * Send WhatsApp message with buttons (for admin approval)
     * Note: Wa Nugasin free tier may not support buttons
     * This will send as regular message with button-like text
     * 
     * @param string $to Phone number
     * @param string $message Message content
     * @param array $buttons Button configuration [['id' => 'btn1', 'text' => 'Button 1']]
     * @return array Response data
     */
    public function sendMessageWithButtons(string $to, string $message, array $buttons = []): array
    {
        try {
            // Add button instructions to message
            $buttonText = "\n\n━━━━━━━━━━━━━━━━━━━━\n";
            $buttonText .= "*Ketik angka untuk memproses:*\n";
            
            foreach ($buttons as $index => $button) {
                $buttonText .= ($index + 1) . ". " . $button['text'] . "\n";
            }
            
            $buttonText .= "\n*Contoh ketik: 1*";
            
            $fullMessage = $message . $buttonText;

            // Send as regular message (Wa Nugasin free tier doesn't support interactive buttons)
            return $this->sendMessage($to, $fullMessage);

        } catch (\Exception $e) {
            Log::error('Wa Nugasin: Exception occurred in sendButton', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send message to admin number (configured in .env)
     */
    public function sendToAdmin(string $message): array
    {
        return $this->sendMessage($this->adminNumber, $message);
    }

    /**
     * Check API connection
     */
    public function ping(): bool
    {
        try {
            $response = Http::asForm()->post($this->baseUrl . '/webhook', [
                'key' => $this->apiKey,
                'no' => $this->adminNumber,
                'text' => 'ping',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
