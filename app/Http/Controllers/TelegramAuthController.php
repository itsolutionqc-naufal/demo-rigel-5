<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramAuthController extends Controller
{
    /**
     * Show Telegram login page
     */
    public function showLogin()
    {
        return view('auth.telegram-login');
    }

    /**
     * Handle Telegram auth callback
     */
    public function handleCallback(Request $request)
    {
        // Validate Telegram auth data
        $authData = $request->only([
            'id',
            'first_name',
            'last_name',
            'username',
            'photo_url',
            'auth_date',
            'hash',
        ]);

        Log::info('Telegram auth callback', ['data' => $authData]);

        // Verify hash from Telegram
        if (!$this->verifyTelegramData($authData)) {
            Log::error('Telegram auth verification failed');
            return redirect()->route('login')
                ->with('error', 'Invalid Telegram authentication');
        }

        // Find or create user
        $user = User::firstOrCreate(
            ['telegram_id' => $authData['id']],
            [
                'name' => trim(($authData['first_name'] ?? '') . ' ' . ($authData['last_name'] ?? '')),
                'email' => $authData['username'] . '@telegram.user',
                'username' => $authData['username'] ?? null,
                'avatar' => $authData['photo_url'] ?? null,
                'password' => bcrypt(uniqid()),
                'email_verified_at' => now(),
            ]
        );

        // Login the user
        Auth::login($user);

        Log::info('User logged in via Telegram', [
            'user_id' => $user->id,
            'telegram_id' => $authData['id'],
        ]);

        // Redirect to dashboard
        return redirect()->intended('/admin/dashboard');
    }

    /**
     * Verify Telegram auth data
     */
    protected function verifyTelegramData(array $authData): bool
    {
        if (!isset($authData['hash'])) {
            return false;
        }

        $checkHash = $authData['hash'];
        unset($authData['hash']);

        // Sort data alphabetically
        ksort($authData);

        // Create data check string
        $dataCheckString = collect($authData)
            ->map(fn($value, $key) => "{$key}={$value}")
            ->join("\n");

        // Create secret key
        $secretKey = hash('sha256', config('services.telegram.bot_token'), true);

        // Calculate hash
        $calculatedHash = hash_hmac('sha256', $dataCheckString, $secretKey);

        // Verify hash
        return hash_equals($calculatedHash, $checkHash);
    }
}
