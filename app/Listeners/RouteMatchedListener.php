<?php

namespace App\Listeners;

use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RouteMatchedListener
{
    /**
     * Handle the event.
     *
     * @param  RouteMatched  $event
     * @return void
     */
    public function handle(RouteMatched $route_matched)
    {
        $message = 'Route \'' . $route_matched->route->uri . '\' matched with methods (' . implode(",", $route_matched->route->methods) . ')';
        if($route_matched->route->uri == 'home'){
            $message = $message . ' and request ' . "\n" . $route_matched->request;
        }
        Log::info($message);
    }
}