<?php

use App\Http\Controllers\DeviceTokenController;
use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for Mobile App - Firebase Cloud Messaging
|--------------------------------------------------------------------------
*/

// Device Token Registration
Route::post('/device-tokens', [DeviceTokenController::class, 'store']);

// Test Notification (send to specific user)
Route::post('/test-notification', function (Request $request) {
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'title' => 'required|string',
        'body' => 'required|string',
    ]);

    $user = User::findOrFail($request->user_id);
    
    $messaging = app(\Kreait\Firebase\Contract\Messaging::class);
    $service = new PushNotificationService($messaging);
    
    $service->sendToUser(
        $user->id,
        $request->title,
        $request->body,
        ['type' => 'test']
    );

    return response()->json([
        'success' => true,
        'message' => 'Notification sent to user: ' . $user->name
    ]);
});

// Broadcast Notification (send to all users)
Route::post('/broadcast-notification', function (Request $request) {
    $request->validate([
        'title' => 'required|string',
        'body' => 'required|string',
    ]);

    $messaging = app(\Kreait\Firebase\Contract\Messaging::class);
    $service = new PushNotificationService($messaging);
    
    $users = User::where('role', '!=', 'admin')->get();
    
    $sent = 0;
    foreach ($users as $user) {
        $service->sendToUser($user->id, $request->title, $request->body);
        $sent++;
    }

    return response()->json([
        'success' => true,
        'message' => "Notification sent to {$sent} users"
    ]);
});