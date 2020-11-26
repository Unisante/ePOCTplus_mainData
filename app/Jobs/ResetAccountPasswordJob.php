<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResetAccountPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $body;
    protected $email;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($body,$email)
    {
        $this->body=$body;
        $this->email=$email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      \Mail::to($this->email)->send(new \App\Mail\ForgotPasswordMail($this->body));
    }
}
