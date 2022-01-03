<?php

namespace App\Providers;

use DOMDocument;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
      if (env('APP_ENV') !== 'local') {
          //  $url->forceScheme('https');
         $url->forceScheme('https');
      }
    }

}
