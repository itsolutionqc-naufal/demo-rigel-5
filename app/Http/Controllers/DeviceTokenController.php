<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Device token registration attempt', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
        ]);

        $user = Auth::user();

        $validated = $request->validate([
            'token' => 'required|string|max:4096',
            'platform' => 'required|in:android,ios',
        ]);

        $hash = hash('sha256', $validated['token']);

        $deviceToken = DeviceToken::firstOrNew(['token_hash' => $hash]);
        $deviceToken->user_id = $user->id;
        $deviceToken->token = $validated['token'];
        $deviceToken->token_hash = $hash;
        $deviceToken->platform = $validated['platform'];
        $deviceToken->last_seen_at = now();
        $deviceToken->save();

        // Also update the main users table for convenience
        $user->update(['fcm_token' => $validated['token']]);

        return response()->json([
            'success' => true,
            'id' => $deviceToken->id,
        ]);
    }
}
