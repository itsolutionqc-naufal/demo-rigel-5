<?php

namespace App\Http\Controllers;

use App\Models\SaleTransaction;
use App\Models\User;
use App\Services\CommissionService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WhatsAppCallbackController extends Controller
{
    /**
     * Handle WhatsApp webhook from Fonnte
     * Endpoint: /webhook/register
     * 
     * Webhook format from Fonnte (POST):
     * {
     *   "from": "628987654321",    // Nomor WA pengirim (admin yang reply)
     *   "text": "Hai, ada pesan masuk!",
     *   "type": "text",            // Tipe pesan
     *   "timestamp": 1678886400
     * }
     */
    public function handleWebhook(Request $request)
    {
        // Ensure this handles only POST requests
        if ($request->method() !== 'POST') {
            Log::warning('WhatsApp webhook received non-POST request', [
                'method' => $request->method(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Method not allowed',
            ], 405);
        }

        // Get JSON payload from request body
        $data = $request->all();

        Log::info('Fonnte Webhook received', [
            'data' => $data,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Validate required fields
        if (!isset($data['from']) || !isset($data['text'])) {
            Log::warning('Invalid webhook payload - missing required fields', [
                'has_from' => isset($data['from']),
                'has_text' => isset($data['text']),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid webhook payload',
            ], 400);
        }

        // Extract data
        $from = $data['from'];           // Admin phone number who replied
        $text = trim($data['text']);     // Message text (should be "1" or "2")
        $type = $data['type'] ?? 'text';
        $timestamp = $data['timestamp'] ?? now();

        // Fonnte doesn't send messageId, so we use fallback mechanism
        $messageId = null;

        // Check if this is a button response (admin ketik "1" or "2")
        if (in_array($text, ['1', '2'])) {
            Log::info('Button response detected', [
                'from' => $from,
                'text' => $text,
                'type' => $type,
            ]);

            return $this->handleButtonResponse($from, $text, $messageId, $from);
        }

        // Handle other messages (for future features)
        Log::info('Regular message received (not a button response)', [
            'from' => $from,
            'text' => $text,
            'type' => $type,
        ]);

        // Return success for non-button messages
        return response()->json([
            'status' => 'received',
            'message' => 'Message received (waiting for button response)',
        ]);
    }

    /**
     * Handle button response from admin
     * Admin ketik "1" untuk Berhasil, "2" untuk Gagal
     * 
     * @param string $from Admin phone number who replied
     * @param string $buttonId Button clicked ("1" or "2")
     * @param string|null $messageId Original message ID from Wa Nugasin
     * @param string|null $sessionId Session ID (admin's WA number)
     */
    protected function handleButtonResponse(string $from, string $buttonId, ?string $messageId, ?string $sessionId)
    {
        try {
            // Try to get transaction code from message ID (cache)
            $transactionCode = null;
            
            if ($messageId) {
                $transactionCode = $this->getTransactionCodeFromMessageId($messageId);
            }

            if (!$transactionCode) {
                // Fallback: Get the most recent pending transaction
                // This happens when:
                // 1. API doesn't return message_id (Wa Nugasin free tier)
                // 2. Cache expired
                // 3. Webhook called without message_id
                Log::warning('Transaction code not found in cache, using fallback', [
                    'message_id' => $messageId,
                    'from' => $from,
                    'session_id' => $sessionId,
                ]);

                // Get the most recent pending topup transaction
                $transaction = SaleTransaction::where('status', 'pending')
                    ->where('transaction_type', 'topup')
                    ->latest()
                    ->first();

                if (!$transaction) {
                    Log::warning('No pending topup transaction found');

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Tidak ada transaksi pending yang ditemukan',
                    ], 404);
                }

                $transactionCode = $transaction->transaction_code;
                Log::info('Using fallback transaction', [
                    'transaction_code' => $transactionCode,
                ]);
            } else {
                // Find transaction by code
                $transaction = SaleTransaction::where('transaction_code', $transactionCode)->first();

                if (!$transaction) {
                    Log::warning('Transaction not found', [
                        'code' => $transactionCode,
                    ]);

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Transaksi tidak ditemukan',
                    ], 404);
                }
            }

            // Process based on button clicked (1 = Berhasil, 2 = Gagal)
            if ($buttonId === '1') {
                return $this->markTransactionAsSuccess($transaction, $from);
            } elseif ($buttonId === '2') {
                return $this->markTransactionAsFailed($transaction, $from);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Respon tidak dikenali. Ketik "1" untuk Berhasil atau "2" untuk Gagal',
            ], 400);

        } catch (\Exception $e) {
            Log::error('Error handling WhatsApp callback', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error processing callback',
            ], 500);
        }
    }

    /**
     * Mark transaction as success
     */
    protected function markTransactionAsSuccess(SaleTransaction $transaction, string $adminPhone)
    {
        DB::beginTransaction();

        try {
            // Find admin user by phone
            $admin = User::where('phone', $adminPhone)
                ->orWhere('whatsapp_number', $adminPhone)
                ->first();

            // Update transaction status
            $transaction->update([
                'status' => 'success',
                'admin_id' => $admin?->id,
                'confirmed_at' => now(),
                'completed_at' => now(),
            ]);

            // Commission will be automatically calculated by SaleTransaction model observer

            DB::commit();

            Log::info('Transaction marked as success via WhatsApp', [
                'transaction_id' => $transaction->id,
                'admin_phone' => $adminPhone,
            ]);

            // Send confirmation to admin
            $this->sendConfirmationToAdmin($transaction, 'success');

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil ditandai sebagai BERHASIL',
                'transaction_id' => $transaction->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error marking transaction as success', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating transaction',
            ], 500);
        }
    }

    /**
     * Mark transaction as failed
     */
    protected function markTransactionAsFailed(SaleTransaction $transaction, string $adminPhone)
    {
        DB::beginTransaction();

        try {
            // Find admin user by phone
            $admin = User::where('phone', $adminPhone)
                ->orWhere('whatsapp_number', $adminPhone)
                ->first();

            // Update transaction status
            $transaction->update([
                'status' => 'failed',
                'admin_id' => $admin?->id,
                'completed_at' => now(),
            ]);

            DB::commit();

            Log::info('Transaction marked as failed via WhatsApp', [
                'transaction_id' => $transaction->id,
                'admin_phone' => $adminPhone,
            ]);

            // Send confirmation to admin
            $this->sendConfirmationToAdmin($transaction, 'failed');

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil ditandai sebagai GAGAL',
                'transaction_id' => $transaction->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error marking transaction as failed', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating transaction',
            ], 500);
        }
    }

    /**
     * Get transaction code from message ID
     * You can store this mapping in cache when sending message
     */
    protected function getTransactionCodeFromMessageId(string $messageId): ?string
    {
        // Try to get from cache
        $transactionCode = cache()->get("wa_message_{$messageId}");

        if ($transactionCode) {
            // Delete from cache after retrieving
            cache()->forget("wa_message_{$messageId}");
        }

        return $transactionCode;
    }

    /**
     * Send confirmation message to admin
     */
    protected function sendConfirmationToAdmin(SaleTransaction $transaction, string $status)
    {
        // Get admin WhatsApp number from settings
        $adminPhone = \App\Models\Setting::getWhatsAppNumber();

        $waService = app(\App\Services\WaNugasinService::class);

        $statusText = $status === 'success' ? 'BERHASIL' : 'GAGAL';
        $emoji = $status === 'success' ? '✅' : '❌';

        $message = "{$emoji} *KONFIRMASI UPDATE TRANSAKSI* {$emoji}\n\n";
        $message .= "Kode Transaksi: *{$transaction->transaction_code}*\n";
        $message .= "Layanan: {$transaction->service_name}\n";
        $message .= "ID Pengguna: {$transaction->user_id_input}\n";
        $message .= "Nominal: Rp " . number_format($transaction->amount, 0, ',', '.') . "\n";
        $message .= "Status: *{$statusText}*\n\n";
        $message .= "Terima kasih telah memproses transaksi ini.";

        $waService->sendMessage($adminPhone, $message);
    }

    /**
     * Webhook for Wa Nugasin status updates
     */
    public function handleStatusUpdate(Request $request)
    {
        Log::info('WhatsApp Status Update received', [
            'data' => $request->all(),
        ]);

        $messageId = $request->input('messageId');
        $status = $request->input('status'); // sent, delivered, read, failed

        Log::info('Message status updated', [
            'message_id' => $messageId,
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status update received',
        ]);
    }
}
