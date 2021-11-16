<?php

namespace App\Providers;

use Route;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

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
            // Here the routes to manage clients are guarded with additionnal middleware which requires the Manage_Devices permission
        Route::group(['middleware'=>['web','auth']], function(){ 
            Passport::routes(function ($router) {
                $router->forClients();
            });
        });
    }
}
