<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectNonAdminToMobileApp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isMarketing()) {
            return redirect()->route('marketing.dashboard');
        }

        // Admin bisa akses mobile app (untuk registrasi device token FCM)
        // Hanya redirect ke dashboard jika bukan dari Capacitor
        if ($user && !$user->isAdmin() && !$user->isMarketing()) {
            return redirect()->route('mobile.app');
        }

        return $next($request);
    }
}
