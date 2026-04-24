<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.only' => \App\Http\Middleware\AdminOnly::class,
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
            'marketing.only' => \App\Http\Middleware\MarketingOnly::class,
            'nonadmin.to.app' => \App\Http\Middleware\RedirectNonAdminToMobileApp::class,
            'prevent.back' => \App\Http\Middleware\PreventBackHistory::class,
            'role' => \App\Http\Middleware\Role::class,
        ]);

        // Disable caching/bfcache for all web pages to prevent sensitive pages
        // (e.g., admin dashboard) from being restored when users press Back.
        $middleware->appendToGroup('web', \App\Http\Middleware\PreventBackHistory::class);

        // Trust reverse proxies (tunnel / load balancer) so X-Forwarded-* headers
        // are respected (fix mixed-content assets when accessed via HTTPS tunnel).
        $middleware->trustProxies(at: '*');

        // Skip CSRF verification for Telegram webhook
        $middleware->validateCsrfTokens(except: [
            'telegram/webhook',
        ]);
    })
    ->withProviders([
        \App\Providers\AppServiceProvider::class,
        \App\Providers\AuthServiceProvider::class,
        \App\Providers\FortifyServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
