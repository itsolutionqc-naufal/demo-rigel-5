<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     * These routes are stateless webhooks — no session or cookie needed.
     */
    protected $except = [
        'telegram/webhook',
        'telegram/webhook/*',
        'webhook/register',
        'whatsapp/status',
    ];
}
