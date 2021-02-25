<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $body;
    protected $random_password;
    protected $username;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($body,$username,$random_password)
    {
      $this->body = $body;
      $this->username = $username;
      $this->random_password = $random_password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->subject('Password Reset')
      ->view('emails.forgotPassword')->with(
        ['body'=>$this->body,
        'username'=>$this->username,
        'code'=>$this->random_password]);
    }
}
