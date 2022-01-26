<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;

class LogFailedAuthenticationAttempt
{
    /**
     * Handle the event.
     *
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        if($event->user){
            Log::error("User tried to log in, but failed.", ["user_id" => $event->user->id, "user_email" => $event->user->email]);
        }else{
            Log::error("User tried to log in with email address " . $event->credentials['email'] . ", but it does not exist.");
        }
    }
}