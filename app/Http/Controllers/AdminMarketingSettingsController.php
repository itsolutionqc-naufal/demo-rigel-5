<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AdminMarketingSettingsController extends Controller
{
    public function index()
    {
        $bonusMode = (string) Setting::get('marketing.user_create_bonus_mode', 'nominal');

        if (! in_array($bonusMode, ['nominal', 'percent', 'default'], true)) {
            $bonusMode = 'nominal';
        }

        $defaultBaseAmount = (int) config('marketing.user_create_bonus_amount', 1000);

        $bonusAmount = (int) Setting::get('marketing.user_create_bonus_amount', $defaultBaseAmount);
        $bonusPercent = (float) Setting::get('marketing.user_create_bonus_percent', 10);

        $effectiveBonusAmount = match ($bonusMode) {
            'default' => $defaultBaseAmount,
            'percent' => (int) round($defaultBaseAmount * ($bonusPercent / 100)),
            default => $bonusAmount,
        };

        $downloadPromptEnabled = (bool) Setting::get('app.download_prompt_enabled', true);
        $downloadUrl = (string) Setting::get('app.download_url', '');
        $downloadPromptTitle = (string) Setting::get('app.download_prompt_title', 'Download Aplikasi RigelCoin');
        $downloadPromptBody = (string) Setting::get('app.download_prompt_body', 'Beli & jual coin lebih cepat, pantau transaksi, dan klaim bonus/komisi langsung dari aplikasi.');

        return view('admin.marketing-settings.index', compact(
            'bonusMode',
            'bonusAmount',
            'bonusPercent',
            'defaultBaseAmount',
            'effectiveBonusAmount',
            'downloadPromptEnabled',
            'downloadUrl',
            'downloadPromptTitle',
            'downloadPromptBody',
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'bonus_mode' => 'required|in:nominal,percent,default',
            'user_create_bonus_amount' => 'nullable|integer|min:0',
            'user_create_bonus_percent' => 'nullable|numeric|min:0|max:100',
            'download_prompt_enabled' => 'nullable|in:0,1',
            'download_url' => 'nullable|string|max:2048',
            'download_prompt_title' => 'nullable|string|max:120',
            'download_prompt_body' => 'nullable|string|max:240',
        ]);

        $mode = (string) $validated['bonus_mode'];

        Setting::set('marketing.user_create_bonus_mode', $mode, 'text', 'marketing');

        if ($mode === 'nominal') {
            $amount = (int) ($validated['user_create_bonus_amount'] ?? config('marketing.user_create_bonus_amount', 1000));
            Setting::set('marketing.user_create_bonus_amount', $amount, 'number', 'marketing');
        }

        if ($mode === 'percent') {
            $percent = (float) ($validated['user_create_bonus_percent'] ?? 0);
            Setting::set('marketing.user_create_bonus_percent', $percent, 'number', 'marketing');
        }

        $enabled = (bool) ($validated['download_prompt_enabled'] ?? false);
        Setting::set('app.download_prompt_enabled', $enabled ? '1' : '0', 'boolean', 'app');

        $downloadUrl = (string) ($validated['download_url'] ?? '');
        Setting::set('app.download_url', $downloadUrl, 'text', 'app');

        $title = (string) ($validated['download_prompt_title'] ?? 'Download Aplikasi RigelCoin');
        $body = (string) ($validated['download_prompt_body'] ?? 'Beli & jual coin lebih cepat, pantau transaksi, dan klaim bonus/komisi langsung dari aplikasi.');
        Setting::set('app.download_prompt_title', $title, 'text', 'app');
        Setting::set('app.download_prompt_body', $body, 'text', 'app');

        return redirect()
            ->route('admin.marketing-settings')
            ->with('success', 'Pengaturan marketing berhasil diperbarui.');
    }
}
