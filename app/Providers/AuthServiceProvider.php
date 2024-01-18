<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        Gate::define('define-access-admin', function (User $user) {
            $roleAccess = [1];
            return in_array($user->role_id, $roleAccess);
        });
        Gate::define('define-access-pastor', function (User $user) {
            $roleAccess = [1, 2];
            return in_array($user->role_id, $roleAccess);
        });
        Gate::define('define-access-voluntary', function (User $user) {
            $roleAccess = [1, 2, 3];
            return in_array($user->role_id, $roleAccess);
        });
        Gate::define('define-access-user', function (User $user) {
            $roleAccess = [1, 2, 3, 4];
            return in_array($user->role_id, $roleAccess);
        });

        //individual
        Gate::define('access-exclusive-voluntary', function (User $user) {
            $roleAccess = [3];
            return in_array($user->role_id, $roleAccess);
        });
    }
}
