<?php

namespace App\Providers;

use App\Models\User;
use App\Support\RoleName;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::before(function (mixed $user): ?bool {
            if (! $user instanceof User) {
                return null;
            }

            return $user->hasRole(RoleName::SuperAdmin) ? true : null;
        });
    }
}
