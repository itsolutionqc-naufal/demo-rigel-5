<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
