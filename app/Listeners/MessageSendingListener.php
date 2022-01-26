<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;

class MessageSendingListener
{
    /**
     * Handle the event.
     *
     * @param  MessageSending  $event
     * @return void
     */
    public function handle(MessageSending $event)
    {
        Log::info('Message sent.', ["message" => $event->message]);
    }
}