<?php

namespace App\Providers;

use Route;
use App\Device;
use App\Policies\DevicePolicy;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
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

        //
        //All other passport routes:
        //Only keep the necessary routes, uncomment other groups if needed in the future
        Passport::routes(function ($router) {
                    $router->forAuthorization();
                    $router->forAccessTokens();
                    //$router->forTransientTokens();
                    //$router->forPersonalAccessTokens();
                });
    }
}
