<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage: ->middleware('role:admin') or ->middleware('role:admin,user')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $allowedRoles = collect($roles)
            ->flatMap(fn (string $r) => array_filter(array_map('trim', explode(',', $r))))
            ->filter()
            ->values()
            ->all();

        if ($allowedRoles === []) {
            return $next($request);
        }

        $userRole = (string) ($user->getAttribute('role') ?? '');
        $isAdmin = (bool) ($user->getAttribute('is_admin') ?? false) || $userRole === 'admin' || (int) $user->getAttribute('id') === 1;

        if (in_array('admin', $allowedRoles, true) && $isAdmin) {
            return $next($request);
        }

        if ($userRole !== '' && in_array($userRole, $allowedRoles, true)) {
            return $next($request);
        }

        abort(403);
    }
}

