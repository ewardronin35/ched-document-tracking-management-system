<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomPasswordReset extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Determine the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Reset Your Password')
                    ->view('emails.ched_verification', [
                        'actionText' => 'Reset Password',
                        'actionUrl' => url(route('password.reset', [
                            'token' => $this->token,
                            'email' => $notifiable->email,
                        ], false)),
                        'greeting' => 'Hello!',
                        'token' => $this->token,
                        'email' => $notifiable->email,
                    ]);
    }
}
