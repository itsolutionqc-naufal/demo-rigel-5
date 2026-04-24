<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
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

        return response()->json([
            'success' => true,
            'id' => $deviceToken->id,
        ]);
    }
}
