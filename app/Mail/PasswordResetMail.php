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
        return $this->markdown('emails.password-reset')
            ->subject('Set Your Password')
            ->with([
                'name' => $this->user->name,
                'resetLink' => url('/password/reset', $this->token) . '?email=' . urlencode($this->user->email),
            ]);
    }
}
