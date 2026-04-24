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

        if ($user->isAdmin()) {
            return redirect()->intended(route('dashboard'));
        }

        if ($user->isMarketing()) {
            return redirect()->intended(route('marketing.dashboard'));
        }

        return redirect()->intended(route('mobile.app'));
    }
}
