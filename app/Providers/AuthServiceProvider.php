<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Route;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
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

        Passport::routes(function ($router) {
                    $router->forAuthorization();
                    $router->forAccessTokens();
                    $router->forTransientTokens();
                    $router->forPersonalAccessTokens();
                });
            // Here the routes to manage clients are guarded with additionnal middleware
        Route::group(['middleware'=>['web','auth','permission:Manage_Devices']], function(){ 
            Passport::routes(function ($router) {
                $router->forClients();
            });
        });
    }
}
