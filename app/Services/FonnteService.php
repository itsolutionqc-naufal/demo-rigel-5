<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Fonnte WhatsApp Gateway Service
 * Documentation: https://docs.fonnte.com/
 */
class FonnteService
{
    protected string $token;
    protected string $baseUrl;
    protected string $adminNumber;

    public function __construct()
    {
        $this->token = config('services.fonnte.token', env('FONNTE_TOKEN'));
        $this->baseUrl = config('services.fonnte.base_url', env('FONNTE_BASE_URL', 'https://api.fonnte.com'));
        $this->adminNumber = env('FONNTE_ADMIN_NUMBER', '');
    }

    /**
     * Send WhatsApp message via Fonnte
     *
     * @param string $to Phone number (e.g., '6281234567890')
     * @param string $message Message content
     * @param string|null $filePath Optional file path for attachment
     * @return array Response data
     */
    public function sendMessage(string $to, string $message, ?string $filePath = null): array
    {
        try {
            $endpoint = $this->baseUrl . '/send';

            // Prepare request data
            $data = [
                'target' => $to,
                'message' => $message,
            ];

            // Add file if provided
            if ($filePath) {
                $data['file'] = $this->prepareFileForUpload($filePath);
            }

            // Send request with Fonnte API
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asMultipart()->post($endpoint, $data);

            $result = $response->json();

            // Check response from Fonnte
            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                Log::info('Fonnte: Message sent successfully', [
                    'to' => $to,
                    'response' => $result,
                ]);

                return [
                    'success' => true,
                    'message_id' => $result['id'] ?? $this->generateMessageId(),
                    'response' => $result,
                ];
            }

            Log::error('Fonnte: Failed to send message', [
                'to' => $to,
                'error' => $result['reason'] ?? $result['message'] ?? 'Unknown error',
            ]);

            return [
                'success' => false,
                'error' => $result['reason'] ?? $result['message'] ?? 'Failed to send',
                'response' => $result,
            ];

        } catch (\Exception $e) {
            Log::error('Fonnte: Exception occurred', [
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
     * Send message with text (Fonnte doesn't support interactive buttons)
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

            // Send as regular message
            return $this->sendMessage($to, $fullMessage);

        } catch (\Exception $e) {
            Log::error('Fonnte: Exception occurred in sendButton', [
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
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->get($this->baseUrl . '/account');

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Generate unique message ID for tracking
     */
    protected function generateMessageId(): string
    {
        return 'FONNTE-' . uniqid() . '-' . time();
    }

    /**
     * Prepare file for upload
     */
    protected function prepareFileForUpload(string $filePath): \CURLFile|string
    {
        $fullPath = public_path($filePath);
        
        if (file_exists($fullPath)) {
            return new \CURLFile($fullPath);
        }

        return $filePath;
    }
}
