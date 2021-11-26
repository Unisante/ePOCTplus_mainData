<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Generate2faJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $body;
    protected $email;
    protected $username;
    protected $code;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($body, $email, $username, $code)
    {
        $this->body = $body;
        $this->email = $email;
        $this->username = $username;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      \Mail::to($this->email)->send(new \App\Mail\Generate2faMail($this->body,$this->username,$this->code));
    }
}
