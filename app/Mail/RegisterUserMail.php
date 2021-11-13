<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterUserMail extends Mailable
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
        return $this->view('emails.register-user')
            ->from('register.user@no-reply-sigma-test.com')
            ->subject('Nouvelle demande d\'inscription')
            ->with([
                'email'     => $this->data['email'],
                'lastname'  => $this->data['lastname'],
                'firstname' => $this->data['firstname'],
            ]);
    }
}
