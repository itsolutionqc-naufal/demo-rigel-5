<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // Clear cache setelah login untuk memastikan data terbaru
        Cache::forget('services.active');
        Cache::forget('payment_methods');

        $user = Auth::user();

        // Jika request dari Capacitor/mobile app, arahkan semua role ke mobile app
        // agar device token bisa terdaftar
        $isCapacitor = $request->header('X-Capacitor-Platform') 
            || str_contains($request->header('User-Agent', ''), 'Capacitor')
            || $request->header('X-Requested-With') === 'XMLHttpRequest';

        if ($user->isAdmin() && !$isCapacitor) {
            return redirect()->intended(route('dashboard'));
        }

        if ($user->isMarketing() && !$isCapacitor) {
            return redirect()->intended(route('marketing.dashboard'));
        }

        return redirect()->intended(route('mobile.app'));
    }
}
