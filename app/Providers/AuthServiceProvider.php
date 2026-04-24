<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\AdminDashboardPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy::class',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Register custom policies
        Gate::policy(User::class, AdminDashboardPolicy::class);
        Gate::policy(\App\Models\PaymentMethod::class, \App\Policies\PaymentMethodPolicy::class);
        Gate::policy(\App\Models\SaleTransaction::class, \App\Policies\SaleTransactionPolicy::class);
    }
}
