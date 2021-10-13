<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Config;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Device' => 'App\Policies\DevicePolicy',
        'App\HealthFacility' => 'App\Policies\HealthFacilityPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //All other passport routes:
        //Only keep the necessary routes, uncomment other groups if needed in the future
        Passport::routes(function ($router) {
            $router->forAuthorization();
            $router->forAccessTokens();
            //$router->forTransientTokens();
            //$router->forPersonalAccessTokens();
        });
        Passport::tokensExpireIn(now()->addDays(Config::get('medal.authentication.token_lifetime_days')));
        Passport::refreshTokensExpireIn(now()->addDays(Config::get('medal.authentication.refresh_token_lifetime_days')));
    }
}
