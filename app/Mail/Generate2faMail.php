<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Generate2faMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $body;
    protected $username;
    protected $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($body, $username, $code)
    {
      $this->body = $body;
      $this->username = $username;
      $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->subject('2FA Generated Code')
      ->view('emails.generate2fa')->with([
        'body'      => $this->body,
        'username'  => $this->username,
        'code'      => $this->code
      ]);
    }
}
