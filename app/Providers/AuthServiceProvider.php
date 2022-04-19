<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

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
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if (Str::contains(url()->current(), '/workouts/') && in_array($ability, ['viewAny', 'view', 'create', 'update', 'delete'])) {
                return null;
            }

            if ($user->hasRole('Super-Admin')) {
                return true;
            }

            if ($user->hasRole('Admin') && $ability != 'Role' && $ability != 'Permission') {
                return true;
            }
        });
    }
}
