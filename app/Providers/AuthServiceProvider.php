<?php

namespace App\Providers;

use App\Auth\Guards\JWTGuard;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
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
    public function boot(): void
    {
        JWT::$leeway = config('sso.jwt-leeway');

        Gate::before(function (User $user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
    }

    public function register(): void
    {
        Auth::extend('jwt', function ($app, $name, array $config) {
            return new JWTGuard(
                Auth::createUserProvider($config['provider']),
                $app->request->bearerToken() ?? '',
                config('sso.key')
            );
        });
    }
}
