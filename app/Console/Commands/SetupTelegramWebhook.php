<?php

namespace App\Console\Commands;

use App\Models\TelegramBot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SetupTelegramWebhook extends Command
{
    protected $signature = 'telegram:setup-webhook
        {url? : Webhook URL (defaults to APP_URL/telegram/webhook)}
        {--bot= : Telegram bot ID from telegram_bots table}
        {--all-active : Set webhook for all active bots}
        {--secret= : Optional Telegram secret_token for webhook validation}';
    protected $description = 'Setup Telegram webhook for button clicks';

    public function handle()
    {
        $url = $this->argument('url')
            ?: rtrim((string) config('app.url'), '/') . '/telegram/webhook';

        if (
            str_contains($url, 'localhost')
            || str_contains($url, '127.0.0.1')
            || str_starts_with($url, 'http://')
        ) {
            $this->warn('Telegram webhook must be a public HTTPS URL.');
            $this->warn('Tip: use Cloudflare Tunnel and set APP_URL=https://<your-hostname>, then run this command without the {url} argument.');
        }

        $secret = (string) ($this->option('secret') ?? '');
        if ($secret === '') {
            $secret = (string) (env('TELEGRAM_WEBHOOK_SECRET') ?? '');
        }

        $botId = $this->option('bot');
        $allActive = (bool) $this->option('all-active');

        if ($botId && $allActive) {
            $this->error('Use either --bot or --all-active (not both).');
            return 1;
        }

        if (! $url) {
            $this->error('Webhook URL is required (either pass {url} or set APP_URL).');
            return 1;
        }

        $targets = collect();

        if ($allActive) {
            $targets = TelegramBot::active()->get();
            if ($targets->isEmpty()) {
                $this->error('No active bots found in telegram_bots.');
                return 1;
            }
        } elseif ($botId) {
            $bot = TelegramBot::find($botId);
            if (! $bot) {
                $this->error("Telegram bot not found: id={$botId}");
                return 1;
            }
            if (! $bot->is_active) {
                $this->warn("Bot id={$botId} is inactive; still setting webhook.");
            }
            $targets = collect([$bot]);
        } else {
            $token = config('services.telegram.bot_token');
            if (! $token) {
                $this->error('No default Telegram token configured (TELEGRAM_BOT_TOKEN).');
                $this->error('Use --bot=<id> or set TELEGRAM_BOT_TOKEN in .env');
                return 1;
            }

            // Synthetic "target" so output stays consistent.
            $targets = collect([
                (object) [
                    'id' => null,
                    'name' => 'Default .env bot',
                    'token' => $token,
                ],
            ]);
        }

        $this->info('Setting up Telegram webhook...');
        $this->info("URL: {$url}");
        if ($secret) {
            $this->info('Secret: configured (will validate via X-Telegram-Bot-Api-Secret-Token)');
        }

        $rows = [];
        $hadFailure = false;

        foreach ($targets as $target) {
            $token = $target->token;
            $label = $target->id ? "#{$target->id} {$target->name}" : (string) $target->name;

            $payload = [
                'url' => $url,
                'allowed_updates' => ['message', 'callback_query'],
            ];
            if ($secret) {
                $payload['secret_token'] = $secret;
            }

            $response = Http::post("https://api.telegram.org/bot{$token}/setWebhook", $payload);
            $result = $response->json();

            if (! ($result['ok'] ?? false)) {
                $hadFailure = true;
                $rows[] = [
                    $label,
                    'FAILED',
                    $result['description'] ?? 'Unknown error',
                    '',
                    '',
                ];
                continue;
            }

            $infoResponse = Http::get("https://api.telegram.org/bot{$token}/getWebhookInfo");
            $info = $infoResponse->json();
            $rows[] = [
                $label,
                'OK',
                $info['result']['url'] ?? 'N/A',
                (string) ($info['result']['pending_update_count'] ?? 0),
                (string) ($info['result']['last_error_message'] ?? ''),
            ];
        }

        $this->table(['Bot', 'Status', 'Webhook URL', 'Pending', 'Last Error'], $rows);

        if ($hadFailure) {
            $this->error('❌ Some webhooks failed to set.');
            return 1;
        }

        $this->info('✅ Webhook successfully set!');
        return 0;
    }
}
