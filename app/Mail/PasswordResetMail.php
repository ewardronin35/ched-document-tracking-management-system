<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\User $user
     * @param string $token
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ched_verification')
            ->subject('Set Your Password')
            ->with([
                'name' => $this->user->name,
                'actionUrl' => route('password.reset', [
                    'token' => $this->token,
                    'email' => $this->user->email,
                ]),
                'actionText' => 'Reset Password',
                'greeting' => 'Hello!',
                'token' => $this->token, // Pass the token
                'email' => $this->user->email, // Pass the email
            ]);
    }
}
