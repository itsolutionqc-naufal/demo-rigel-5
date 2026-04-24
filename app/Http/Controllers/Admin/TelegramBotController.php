<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TelegramBot;
use Illuminate\Http\Request;

class TelegramBotController extends Controller
{
    public function index()
    {
        $bots = TelegramBot::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.telegram-bots.index', compact('bots'));
    }

    public function create()
    {
        return view('admin.telegram-bots.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:telegram_bots',
            'token' => 'required|string|max:255',
            'chat_id' => 'required|string|max:50',
        ]);

        TelegramBot::create([
            'name' => $request->name,
            'username' => $request->username,
            'token' => $request->token,
            'chat_id' => $request->chat_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.telegram-bots.index')
            ->with('success', 'Telegram bot berhasil ditambahkan!');
    }

    public function edit(TelegramBot $telegramBot)
    {
        return view('admin.telegram-bots.edit', compact('telegramBot'));
    }

    public function update(Request $request, TelegramBot $telegramBot)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:telegram_bots,username,' . $telegramBot->id,
            'token' => 'required|string|max:255',
            'chat_id' => 'required|string|max:50',
        ]);

        $telegramBot->update([
            'name' => $request->name,
            'username' => $request->username,
            'token' => $request->token,
            'chat_id' => $request->chat_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.telegram-bots.index')
            ->with('success', 'Telegram bot berhasil diperbarui!');
    }

    public function destroy(TelegramBot $telegramBot)
    {
        if ($telegramBot->services()->count() > 0) {
            return redirect()->route('admin.telegram-bots.index')
                ->with('error', 'Bot tidak dapat dihapus karena masih digunakan oleh layanan.');
        }

        $telegramBot->delete();
        return redirect()->route('admin.telegram-bots.index')
            ->with('success', 'Telegram bot berhasil dihapus!');
    }

    public function toggle(TelegramBot $telegramBot)
    {
        $telegramBot->update(['is_active' => !$telegramBot->is_active]);
        return redirect()->route('admin.telegram-bots.index')
            ->with('success', 'Status bot berhasil diubah!');
    }

    public function test(TelegramBot $telegramBot)
    {
        $tgService = new \App\Services\TelegramService($telegramBot->token, $telegramBot->chat_id);
        
        $result = $tgService->sendMessage(
            $telegramBot->chat_id,
            "🤖 *Test Bot Connection*\n\nBot: {$telegramBot->name}\nStatus: " . ($telegramBot->is_active ? '✅ Active' : '❌ Inactive') . "\nTime: " . now()->format('d M Y H:i:s')
        );

        if ($result['success']) {
            return redirect()->route('admin.telegram-bots.index')
                ->with('success', 'Test message sent successfully!');
        }

        return redirect()->route('admin.telegram-bots.index')
            ->with('error', 'Failed to send test message: ' . ($result['error'] ?? 'Unknown error'));
    }
}
