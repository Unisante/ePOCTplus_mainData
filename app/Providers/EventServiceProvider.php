<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'Illuminate\Auth\Events\Failed' => [
            'App\Listeners\LogFailedAuthenticationAttempt',
        ],
        'Illuminate\Mail\Events\MessageSending' => [
            'App\Listeners\MessageSendingListener'
        ],
        'Illuminate\Routing\Events\RouteMatched' => [
            'App\Listeners\RouteMatchedListener'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
