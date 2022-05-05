<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmRegistrationEmail extends Mailable
{
    use Queueable, SerializesModels;


    public string $confirmation_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $token)
    {
        $this->confirmation_url = env('FRONTEND_EMAIL_CONFIRMATION_URL') . '?token=' . $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Email confirmation')
                    ->view('mails.emailconfirm');
    }
}
