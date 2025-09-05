<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Automatically finding the Policies
        Gate::guessPolicyNamesUsing(function ($modelClass) {
            return 'App\\Policies\\' . class_basename($modelClass) . 'Policy';
        });

        $this->registerPolicies();

        Gate::define('admin', fn(User $u) => $u->role?->Naziv === 'Administrator');

        Gate::define('manager', fn(User $u) => $u->role?->Naziv === 'Menadžer dogadjaja');

        Gate::define('managerOrAdmin', fn(User $u) =>
            in_array($u->role?->Naziv, ['Administrator', 'Menadžer dogadjaja'])
        );

        Gate::define('client', fn(User $u) => $u->role?->Naziv === 'Klijent');
    }
}
