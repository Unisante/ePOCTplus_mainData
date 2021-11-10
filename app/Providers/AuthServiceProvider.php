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
        //All other passport routes
        Passport::routes(function ($router) {
                    $router->forAuthorization();
                    $router->forAccessTokens();
                    $router->forTransientTokens();
                    $router->forPersonalAccessTokens();
                });

        Passport::tokensExpireIn(now()->addMinutes(1));
        Passport::refreshTokensExpireIn(now()->addMinutes(2));
        Passport::personalAccessTokensExpireIn(now()->addMinutes(1));

            // Here the routes to manage clients are guarded with additionnal middleware which requires the Manage_Devices permission
        Route::group(['middleware'=>['web','auth','permission:Manage_Devices']], function(){
            Passport::routes(function ($router) {
                $router->forClients();
            });
        });
    }
}
