<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisteredUserMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.registered-user')
            ->from('registered.user@no-reply-sigma-test.com')
            ->subject('Inscription établie - Sigma')
            ->with([
                'email'     => $this->data['email'],
                'password'  => $this->data['password'],
                'lastname'  => $this->data['lastname'],
                'firstname' => $this->data['firstname'],
            ]);
    }
}
