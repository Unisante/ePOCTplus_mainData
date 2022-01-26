<?php

namespace App\Providers;

use App\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Spatie\Permission\Models\Role' => 'App\Policies\RolesPolicy',
        'App\User' => 'App\Policies\UsersPolicy',
        'App\Patient' => 'App\Policies\PatientsPolicy',
        'App\MedicalCase' => 'App\Policies\MedicalCasesPolicy',
        'App\Node' => 'App\Policies\QuestionsPolicy',
        'App\HealthFacility' => 'App\Policies\HealthFacilityPolicy',
        'App\Device' => 'App\Policies\DevicePolicy',
        'App\MedicalStaff' => 'App\Policies\MedicalStaffPolicy',
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

        Passport::tokensExpireIn(now()->addDay());
        Passport::refreshTokensExpireIn(now()->addYear());

        // Here the routes to manage clients are guarded with additionnal middleware which requires the Manage_Devices permission
        Route::group(['middleware' => ['web', 'auth', 'permission:Manage_Devices']], function () {
            Passport::routes(function ($router) {
                $router->forClients();
            });
        });
    }
}
