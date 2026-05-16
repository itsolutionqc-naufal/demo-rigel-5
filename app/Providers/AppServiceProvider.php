<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Messaging;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Messaging::class, function ($app) {
            $configuredPath = (string) env('FIREBASE_CREDENTIALS', 'storage/app/firebase-service-account.json');
            $credentialsPath = str_starts_with($configuredPath, '/')
                ? $configuredPath
                : base_path($configuredPath);

            if (! file_exists($credentialsPath)) {
                throw new \RuntimeException("Firebase credentials not found at: {$credentialsPath}");
            }

            $credentials = json_decode((string) file_get_contents($credentialsPath), true);
            if (! is_array($credentials)) {
                throw new \RuntimeException('Invalid Firebase credentials JSON at: '.$credentialsPath.' ('.json_last_error_msg().')');
            }

            $factory = (new Factory)
                ->withServiceAccount($credentials);

            return $factory->createMessaging();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (str_contains(request()->getHost(), 'trycloudflare.com')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        $this->configureDefaults();
        $this->configureObservers();
        $this->configureViewComposers();
        $this->configureAuthRedirects();
    }

    protected function configureObservers(): void
    {
        \App\Models\User::observe(\App\Observers\UserObserver::class);
    }

    protected function configureViewComposers(): void
    {
        // Share unread notifications count with all views
        view()->composer('*', \App\View\Composers\NotificationComposer::class);
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }

    protected function configureAuthRedirects(): void
    {
        RedirectIfAuthenticated::redirectUsing(function () {
            $user = auth()->user();

            if (! $user) {
                return route('home');
            }

            return route($user->homeRouteName());
        });
    }
}
