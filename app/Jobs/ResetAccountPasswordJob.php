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
    protected $username;
    protected $random_password;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($body,$email,$username,$random_password)
    {
        $this->body=$body;
        $this->email=$email;
        $this->username=$username;
        $this->random_password=$random_password;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      \Mail::to($this->email)->send(new \App\Mail\ForgotPasswordMail($this->body,$this->username,$this->random_password));
    }
}
