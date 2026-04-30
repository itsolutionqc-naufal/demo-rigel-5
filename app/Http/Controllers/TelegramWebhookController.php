<?php

namespace App\Http\Controllers;

use App\Models\SaleTransaction;
use App\Models\TelegramBot;
use App\Services\CommissionWithdrawalService;
use App\Services\TelegramService;
use App\Support\TelegramMessageFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    protected TelegramService $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Handle Telegram webhook
     * Endpoint: /telegram/webhook
     */
    public function handleWebhook(Request $request)
    {
        $secret = env('TELEGRAM_WEBHOOK_SECRET');
        if (! empty($secret)) {
            $incomingSecret = (string) $request->header('X-Telegram-Bot-Api-Secret-Token', '');
            if (! hash_equals((string) $secret, $incomingSecret)) {
                Log::warning('Telegram webhook secret mismatch', [
                    'ip' => $request->ip(),
                ]);

                // Return 404 to avoid leaking endpoint validity.
                return response()->json(['ok' => false], 404);
            }
        }

        $data = $request->all();

        Log::info('Telegram Webhook received', [
            'data' => $data,
        ]);

        // Handle callback query (button click)
        if (isset($data['callback_query'])) {
            return $this->handleCallbackQuery($data['callback_query']);
        }

        // Handle regular message (for future features)
        if (isset($data['message'])) {
            return $this->handleMessage($data['message']);
        }

        // Acknowledge receipt
        return response()->json(['ok' => true]);
    }

    /**
     * Handle button click (callback query)
     */
    protected function handleCallbackQuery(array $callbackQuery)
    {
        try {
            $callbackId = $callbackQuery['id'];
            $data = $callbackQuery['data'];
            $from = $callbackQuery['from'];
            $chatId = $callbackQuery['message']['chat']['id'];
            $messageId = $callbackQuery['message']['message_id'];
            $messageHasCaption = isset($callbackQuery['message']['caption']) && ! isset($callbackQuery['message']['text']);
            $messageBot = (array) ($callbackQuery['message']['from'] ?? []);

            Log::info('Telegram button clicked', [
                'callback_id' => $callbackId,
                'data' => $data,
                'from' => $from['first_name'] ?? 'Unknown',
            ]);

            // Parse callback data:
            // - New (bot-aware): "approve|{bot_id|default}|TRX-ABC123"
            // - Legacy: "approve_TRX-ABC123" or "reject_TRX-ABC123"
            $botKey = null;
            $action = null;
            $transactionCode = null;

            if (str_contains($data, '|')) {
                [$action, $botKey, $transactionCode] = array_pad(explode('|', $data, 3), 3, null);
            } else {
                [$action, $transactionCode] = array_pad(explode('_', $data, 2), 2, null);
            }

            $telegram = $this->resolveTelegramService($botKey, $messageBot);

            if (!$transactionCode || !$action) {
                $answer = $telegram->answerCallbackQuery($callbackId, 'Data tidak valid');
                if (! ($answer['ok'] ?? false)) {
                    Log::warning('Telegram answerCallbackQuery failed (invalid data)', [
                        'callback_id' => $callbackId,
                        'action' => $action,
                        'bot_key' => $botKey,
                        'transaction_code' => $transactionCode,
                        'answer' => $answer,
                    ]);
                }
                return response()->json(['ok' => true]);
            }

            // Find transaction (normalize code and fallback from tracked message_id cache)
            $normalizedTransactionCode = $this->normalizeTransactionCode($transactionCode);
            $cachedTransactionCode = null;
            if (!$normalizedTransactionCode && $messageId) {
                $cached = cache()->get("tg_message_{$messageId}");
                if (is_string($cached) && $cached !== '') {
                    $cachedTransactionCode = $this->normalizeTransactionCode($cached);
                }
            }

            $lookupCandidates = array_values(array_unique(array_filter([
                $normalizedTransactionCode,
                $cachedTransactionCode,
            ])));

            $transaction = null;
            foreach ($lookupCandidates as $candidateCode) {
                $transaction = SaleTransaction::query()
                    ->where('transaction_code', $candidateCode)
                    ->orWhereRaw('UPPER(transaction_code) = ?', [strtoupper($candidateCode)])
                    ->first();
                if ($transaction) {
                    break;
                }
            }

            if (!$transaction) {
                Log::warning('Telegram callback: transaction not found', [
                    'callback_id' => $callbackId,
                    'action' => $action,
                    'bot_key' => $botKey,
                    'transaction_code' => $transactionCode,
                    'transaction_code_normalized' => $normalizedTransactionCode,
                    'cached_transaction_code' => $cachedTransactionCode,
                    'from_id' => $from['id'] ?? null,
                    'from_name' => $from['first_name'] ?? null,
                    'chat_id' => $chatId,
                    'message_id' => $messageId,
                    'request_host' => $request->getHost(),
                    'raw_data' => $data,
                ]);
                $answer = $telegram->answerCallbackQuery($callbackId, 'Transaksi tidak ditemukan');
                if (! ($answer['ok'] ?? false)) {
                    Log::warning('Telegram answerCallbackQuery failed (transaction not found)', [
                        'callback_id' => $callbackId,
                        'action' => $action,
                        'bot_key' => $botKey,
                        'transaction_code' => $transactionCode,
                        'answer' => $answer,
                    ]);
                }
                return response()->json(['ok' => true]);
            }

            if (in_array($transaction->status, ['success', 'failed'], true)) {
                $answer = $telegram->answerCallbackQuery($callbackId, 'Transaksi sudah diproses');
                if (! ($answer['ok'] ?? false)) {
                    Log::warning('Telegram answerCallbackQuery failed (already processed)', [
                        'callback_id' => $callbackId,
                        'action' => $action,
                        'bot_key' => $botKey,
                        'transaction_code' => $transactionCode,
                        'answer' => $answer,
                    ]);
                }

                $statusLabel = $transaction->status === 'success' ? 'APPROVED' : 'REJECTED';
                $title = $transaction->transaction_type === 'withdrawal'
                    ? ($transaction->status === 'success' ? 'PENARIKAN DISETUJUI' : 'PENARIKAN DITOLAK')
                    : ($transaction->status === 'success' ? 'TRANSAKSI DISETUJUI' : 'TRANSAKSI DITOLAK');

                $confirmMessage = TelegramMessageFormatter::heading($title);
                $confirmMessage .= TelegramMessageFormatter::bullet('Kode', (string) $transaction->transaction_code);
                $confirmMessage .= TelegramMessageFormatter::bullet('Admin', (string) ($from['first_name'] ?? 'Admin'));
                $confirmMessage .= TelegramMessageFormatter::bullet('Waktu', now()->format('d/m/Y H:i:s'));
                $confirmMessage .= TelegramMessageFormatter::divider();
                if ($transaction->transaction_type === 'withdrawal') {
                    $confirmMessage .= TelegramMessageFormatter::bullet('Nominal', 'Rp ' . number_format((float) $transaction->amount, 0, ',', '.'), false);
                    $confirmMessage .= TelegramMessageFormatter::bullet('Bank', (string) ($transaction->bank_name ?? $transaction->payment_method ?? '-'));
                    $confirmMessage .= TelegramMessageFormatter::bullet('No. Rek', (string) ($transaction->account_number ?? '-'));
                    $confirmMessage .= TelegramMessageFormatter::bullet('Atas Nama', (string) ($transaction->account_name ?? '-'));
                } else {
                    $confirmMessage .= TelegramMessageFormatter::bullet('Nominal', 'Rp ' . number_format((float) $transaction->amount, 0, ',', '.'), false);
                    $confirmMessage .= TelegramMessageFormatter::bullet('Pembayaran', (string) ($transaction->payment_method ?? '-'));
                    $confirmMessage .= TelegramMessageFormatter::bullet('No. Rekening', (string) ($transaction->payment_number ?? '-'));
                }
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Status', $statusLabel, false);

                app()->terminating(function () use ($chatId, $messageId, $confirmMessage, $messageHasCaption, $telegram) {
                    $result = $messageHasCaption
                        ? $telegram->editMessageCaption($chatId, $messageId, $confirmMessage)
                        : $telegram->editMessageText($chatId, $messageId, $confirmMessage);

                    if (! ($result['ok'] ?? false)) {
                        Log::warning('Telegram edit message failed', [
                            'chat_id' => $chatId,
                            'message_id' => $messageId,
                            'result' => $result,
                            'mode' => $messageHasCaption ? 'caption' : 'text',
                        ]);
                    }
                });

                return response()->json(['ok' => true]);
            }

            // Process based on action
            if ($action === 'approve') {
                return $this->approveTransaction($transaction, $callbackId, $chatId, $messageId, $from, $messageHasCaption, $telegram);
            } elseif ($action === 'reject') {
                return $this->rejectTransaction($transaction, $callbackId, $chatId, $messageId, $from, $messageHasCaption, $telegram);
            }

            $answer = $telegram->answerCallbackQuery($callbackId, 'Aksi tidak dikenali');
            if (! ($answer['ok'] ?? false)) {
                Log::warning('Telegram answerCallbackQuery failed (unknown action)', [
                    'callback_id' => $callbackId,
                    'action' => $action,
                    'bot_key' => $botKey,
                    'transaction_code' => $transactionCode,
                    'answer' => $answer,
                ]);
            }
            return response()->json(['ok' => true]);

        } catch (\Exception $e) {
            Log::error('Error handling Telegram callback', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['ok' => false]);
        }
    }

    /**
     * Approve transaction
     */
    protected function approveTransaction(SaleTransaction $transaction, string $callbackId, int $chatId, int $messageId, array $from, bool $messageHasCaption, TelegramService $telegram)
    {
        DB::beginTransaction();

        try {
            $transaction->update([
                'status' => 'success',
                'confirmed_at' => now(),
                'completed_at' => now(),
            ]);

            DB::commit();

            Log::info('Transaction approved via Telegram', [
                'transaction_code' => $transaction->transaction_code,
            ]);

            // Answer callback query
            $telegram->answerCallbackQuery($callbackId, $transaction->transaction_type === 'host_submit'
                ? 'Host dinyatakan LOLOS!'
                : 'Transaksi berhasil disetujui!');

            Log::info('Editing Telegram message', [
                'chat_id' => $chatId,
                'message_id' => $messageId,
            ]);

            // Edit message to show confirmation (remove buttons)
            if ($transaction->transaction_type === 'host_submit') {
                $formulirValue = '';
                if (!empty($transaction->description)) {
                    $desc = trim((string) $transaction->description);
                    if (str_starts_with($desc, 'Formulir:')) {
                        $formulirValue = trim(substr($desc, strlen('Formulir:')));
                    } else {
                        $formulirValue = $desc;
                    }
                }

                $confirmMessage = TelegramMessageFormatter::heading('HOST LOLOS');
                $confirmMessage .= TelegramMessageFormatter::bullet('Kode', (string) $transaction->transaction_code);
                $confirmMessage .= TelegramMessageFormatter::bullet('Admin', (string) ($from['first_name'] ?? 'Admin'));
                $confirmMessage .= TelegramMessageFormatter::bullet('Waktu', now()->format('d/m/Y H:i:s'));
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Aplikasi', (string) $transaction->service_name);
                $confirmMessage .= TelegramMessageFormatter::bullet('ID Host', (string) $transaction->user_id_input);
                $confirmMessage .= TelegramMessageFormatter::bullet('Nickname', (string) $transaction->nickname);
                $confirmMessage .= TelegramMessageFormatter::bullet('WhatsApp Host', (string) $transaction->whatsapp_number);
                if ($formulirValue !== '') {
                    $confirmMessage .= TelegramMessageFormatter::bullet('Formulir', $formulirValue);
                }
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Status', 'LOLOS');
            } elseif ($transaction->transaction_type === 'withdrawal') {
                $confirmMessage = TelegramMessageFormatter::heading('PENARIKAN DISETUJUI');
                $confirmMessage .= TelegramMessageFormatter::bullet('Kode', (string) $transaction->transaction_code);
                $confirmMessage .= TelegramMessageFormatter::bullet('Admin', (string) ($from['first_name'] ?? 'Admin'));
                $confirmMessage .= TelegramMessageFormatter::bullet('Waktu', now()->format('d/m/Y H:i:s'));
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Nominal', 'Rp ' . number_format((float) $transaction->amount, 0, ',', '.'), false);
                $confirmMessage .= TelegramMessageFormatter::bullet('Bank', (string) ($transaction->bank_name ?? $transaction->payment_method ?? '-'));
                $confirmMessage .= TelegramMessageFormatter::bullet('No. Rek', (string) ($transaction->account_number ?? '-'));
                $confirmMessage .= TelegramMessageFormatter::bullet('Atas Nama', (string) ($transaction->account_name ?? '-'));
                if (! empty($transaction->whatsapp_number)) {
                    $confirmMessage .= TelegramMessageFormatter::bullet('WhatsApp', (string) $transaction->whatsapp_number);
                }
                if (! empty($transaction->address)) {
                    $confirmMessage .= TelegramMessageFormatter::bullet('Alamat', (string) $transaction->address, false);
                }
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Status', 'APPROVED', false);
            } else {
                $confirmMessage = TelegramMessageFormatter::heading('TRANSAKSI DISETUJUI');
                $confirmMessage .= TelegramMessageFormatter::bullet('Kode Transaksi', (string) $transaction->transaction_code);
                $confirmMessage .= TelegramMessageFormatter::bullet('Admin', (string) ($from['first_name'] ?? 'Admin'));
                $confirmMessage .= TelegramMessageFormatter::bullet('Waktu', now()->format('d/m/Y H:i:s'));
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Layanan', (string) $transaction->service_name);
                $confirmMessage .= TelegramMessageFormatter::bullet('ID Pengguna', (string) $transaction->user_id_input);
                $confirmMessage .= TelegramMessageFormatter::bullet('Nickname', (string) $transaction->nickname);
                $confirmMessage .= TelegramMessageFormatter::bullet('Nominal', 'Rp ' . number_format((float) $transaction->amount, 0, ',', '.'), false);
                $confirmMessage .= TelegramMessageFormatter::bullet('Pembayaran', (string) $transaction->payment_method);
                $confirmMessage .= TelegramMessageFormatter::bullet('No. Rekening', (string) $transaction->payment_number);
                if ($transaction->proof_image) {
                    $confirmMessage .= TelegramMessageFormatter::bullet('Bukti Transfer', 'Terlampir');
                }
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Status', 'APPROVED');
            }

            // Schedule edit after response to keep Telegram button snappy.
            app()->terminating(function () use ($chatId, $messageId, $confirmMessage, $messageHasCaption, $telegram) {
                $result = $messageHasCaption
                    ? $telegram->editMessageCaption($chatId, $messageId, $confirmMessage)
                    : $telegram->editMessageText($chatId, $messageId, $confirmMessage);

                if (! ($result['ok'] ?? false)) {
                    Log::warning('Telegram edit message failed', [
                        'chat_id' => $chatId,
                        'message_id' => $messageId,
                        'result' => $result,
                        'mode' => $messageHasCaption ? 'caption' : 'text',
                    ]);
                }
            });

            return response()->json(['ok' => true]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error approving transaction', [
                'error' => $e->getMessage(),
            ]);

            $telegram->answerCallbackQuery($callbackId, 'Gagal menyetujui transaksi');
            return response()->json(['ok' => false]);
        }
    }

    /**
     * Reject transaction
     */
    protected function rejectTransaction(SaleTransaction $transaction, string $callbackId, int $chatId, int $messageId, array $from, bool $messageHasCaption, TelegramService $telegram)
    {
        DB::beginTransaction();

        try {
            $transaction->update([
                'status' => 'failed',
                'completed_at' => now(),
            ]);

            if ($transaction->transaction_type === 'withdrawal') {
                app(CommissionWithdrawalService::class)->restoreForWithdrawal($transaction);
            }

            DB::commit();

            Log::info('Transaction rejected via Telegram', [
                'transaction_code' => $transaction->transaction_code,
            ]);

            // Answer callback query
            $telegram->answerCallbackQuery($callbackId, $transaction->transaction_type === 'host_submit'
                ? 'Host dinyatakan TIDAK LOLOS'
                : 'Transaksi ditolak');

            // Edit message to show confirmation (remove buttons)
            if ($transaction->transaction_type === 'host_submit') {
                $formulirValue = '';
                if (!empty($transaction->description)) {
                    $desc = trim((string) $transaction->description);
                    if (str_starts_with($desc, 'Formulir:')) {
                        $formulirValue = trim(substr($desc, strlen('Formulir:')));
                    } else {
                        $formulirValue = $desc;
                    }
                }

                $confirmMessage = TelegramMessageFormatter::heading('HOST TIDAK LOLOS');
                $confirmMessage .= TelegramMessageFormatter::bullet('Kode', (string) $transaction->transaction_code);
                $confirmMessage .= TelegramMessageFormatter::bullet('Admin', (string) ($from['first_name'] ?? 'Admin'));
                $confirmMessage .= TelegramMessageFormatter::bullet('Waktu', now()->format('d/m/Y H:i:s'));
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Aplikasi', (string) $transaction->service_name);
                $confirmMessage .= TelegramMessageFormatter::bullet('ID Host', (string) $transaction->user_id_input);
                $confirmMessage .= TelegramMessageFormatter::bullet('Nickname', (string) $transaction->nickname);
                $confirmMessage .= TelegramMessageFormatter::bullet('WhatsApp Host', (string) $transaction->whatsapp_number);
                if ($formulirValue !== '') {
                    $confirmMessage .= TelegramMessageFormatter::bullet('Formulir', $formulirValue);
                }
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Status', 'TIDAK LOLOS');
            } elseif ($transaction->transaction_type === 'withdrawal') {
                $confirmMessage = TelegramMessageFormatter::heading('PENARIKAN DITOLAK');
                $confirmMessage .= TelegramMessageFormatter::bullet('Kode', (string) $transaction->transaction_code);
                $confirmMessage .= TelegramMessageFormatter::bullet('Admin', (string) ($from['first_name'] ?? 'Admin'));
                $confirmMessage .= TelegramMessageFormatter::bullet('Waktu', now()->format('d/m/Y H:i:s'));
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Nominal', 'Rp ' . number_format((float) $transaction->amount, 0, ',', '.'), false);
                $confirmMessage .= TelegramMessageFormatter::bullet('Bank', (string) ($transaction->bank_name ?? $transaction->payment_method ?? '-'));
                $confirmMessage .= TelegramMessageFormatter::bullet('No. Rek', (string) ($transaction->account_number ?? '-'));
                $confirmMessage .= TelegramMessageFormatter::bullet('Atas Nama', (string) ($transaction->account_name ?? '-'));
                if (! empty($transaction->whatsapp_number)) {
                    $confirmMessage .= TelegramMessageFormatter::bullet('WhatsApp', (string) $transaction->whatsapp_number);
                }
                if (! empty($transaction->address)) {
                    $confirmMessage .= TelegramMessageFormatter::bullet('Alamat', (string) $transaction->address, false);
                }
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Status', 'REJECTED', false);
            } else {
                $confirmMessage = TelegramMessageFormatter::heading('TRANSAKSI DITOLAK');
                $confirmMessage .= TelegramMessageFormatter::bullet('Kode Transaksi', (string) $transaction->transaction_code);
                $confirmMessage .= TelegramMessageFormatter::bullet('Admin', (string) ($from['first_name'] ?? 'Admin'));
                $confirmMessage .= TelegramMessageFormatter::bullet('Waktu', now()->format('d/m/Y H:i:s'));
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Layanan', (string) $transaction->service_name);
                $confirmMessage .= TelegramMessageFormatter::bullet('ID Pengguna', (string) $transaction->user_id_input);
                $confirmMessage .= TelegramMessageFormatter::bullet('Nickname', (string) $transaction->nickname);
                $confirmMessage .= TelegramMessageFormatter::bullet('Nominal', 'Rp ' . number_format((float) $transaction->amount, 0, ',', '.'), false);
                $confirmMessage .= TelegramMessageFormatter::bullet('Pembayaran', (string) $transaction->payment_method);
                $confirmMessage .= TelegramMessageFormatter::bullet('No. Rekening', (string) $transaction->payment_number);
                if ($transaction->proof_image) {
                    $confirmMessage .= TelegramMessageFormatter::bullet('Bukti Transfer', 'Terlampir');
                }
                $confirmMessage .= TelegramMessageFormatter::divider();
                $confirmMessage .= TelegramMessageFormatter::bullet('Status', 'REJECTED');
            }

            // Schedule edit after response to keep Telegram button snappy.
            app()->terminating(function () use ($chatId, $messageId, $confirmMessage, $messageHasCaption, $telegram) {
                $result = $messageHasCaption
                    ? $telegram->editMessageCaption($chatId, $messageId, $confirmMessage)
                    : $telegram->editMessageText($chatId, $messageId, $confirmMessage);

                if (! ($result['ok'] ?? false)) {
                    Log::warning('Telegram edit message failed', [
                        'chat_id' => $chatId,
                        'message_id' => $messageId,
                        'result' => $result,
                        'mode' => $messageHasCaption ? 'caption' : 'text',
                    ]);
                }
            });

            return response()->json(['ok' => true]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error rejecting transaction', [
                'error' => $e->getMessage(),
            ]);

            $telegram->answerCallbackQuery($callbackId, 'Gagal menolak transaksi');
            return response()->json(['ok' => false]);
        }
    }

    protected function resolveTelegramService(?string $botKey, array $messageBot): TelegramService
    {
        $service = null;

        $messageBotUsername = (string) ($messageBot['username'] ?? '');
        $messageBotId = $messageBot['id'] ?? null; // Telegram bot user id (prefix of bot token)

        // 1) Preferred: botKey from callback_data, but validate it's the same bot that sent this message
        if ($botKey && $botKey !== 'default') {
            $bot = TelegramBot::find($botKey);
            if ($bot && $bot->is_active && filled($bot->token)) {
                $tokenMatches = true;
                $usernameMatches = true;

                if (filled($messageBotId)) {
                    $tokenMatches = str_starts_with((string) $bot->token, (string) $messageBotId . ':');
                }

                if (filled($messageBotUsername)) {
                    $usernameMatches = mb_strtolower((string) $bot->username) === mb_strtolower($messageBotUsername);
                }

                if ($tokenMatches && $usernameMatches) {
                    $service = new TelegramService($bot->token, $bot->chat_id);
                } else {
                    Log::warning('Telegram resolveTelegramService: botKey does not match message bot identity; falling back to message bot resolver', [
                        'bot_key' => $botKey,
                        'message_bot_id' => $messageBotId,
                        'message_bot_username' => $messageBotUsername,
                        'db_bot_id' => $bot->id,
                        'db_bot_username' => $bot->username,
                        'db_token_prefix' => filled($bot->token) ? explode(':', (string) $bot->token, 2)[0] : null,
                    ]);
                }
            }
        }

        // 2) Resolve by the bot that actually sent this message (most reliable)
        if (! $service && filled($messageBotId)) {
            $bot = TelegramBot::where('token', 'like', (string) $messageBotId . ':%')->first();
            if ($bot && $bot->is_active && filled($bot->token)) {
                $service = new TelegramService($bot->token, $bot->chat_id);
            }
        }

        if (! $service && filled($messageBotUsername)) {
            $username = mb_strtolower($messageBotUsername);
            $bot = TelegramBot::whereRaw('LOWER(username) = ?', [$username])->first();
            if ($bot && $bot->is_active && filled($bot->token)) {
                $service = new TelegramService($bot->token, $bot->chat_id);
            }
        }

        return $service ?: $this->telegram;
    }

    /**
     * Normalize callback transaction code from Telegram payload.
     */
    protected function normalizeTransactionCode(?string $code): ?string
    {
        if (! is_string($code)) {
            return null;
        }

        $normalized = preg_replace('/[[:^print:]]/u', '', trim($code));
        if (! is_string($normalized) || $normalized === '') {
            return null;
        }

        return strtoupper($normalized);
    }

    /**
     * Handle regular message
     */
    protected function handleMessage(array $message)
    {
        return response()->json(['ok' => true]);
    }
}
