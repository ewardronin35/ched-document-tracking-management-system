<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class DocumentSubmitted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function via($notifiable)
    {
        return ['vonage'];
    }

    public function toVonage($notifiable)
    {
        return (new VonageMessage)
            ->content('Your document has been successfully submitted and is under review. Thank you for using our service!');
    }
}
