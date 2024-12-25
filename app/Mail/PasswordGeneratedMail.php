<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordGeneratedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;     // Declare the user property to be accessible in the email template
    public $password; // Declare the password property

    /**
     * Create a new message instance.
     *
     * @param \App\Models\User $user
     * @param string $password
     */
    public function __construct($user, $password)
    {
        $this->user = $user;       // Assign the user data
        $this->password = $password; // Assign the password data
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.password-generated')
            ->subject('Your New Password')
            ->with([
                'user' => $this->user,       // Pass the user to the view
                'password' => $this->password, // Pass the password to the view
            ]);
    }
}
