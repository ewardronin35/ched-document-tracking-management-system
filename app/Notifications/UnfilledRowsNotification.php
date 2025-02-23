<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class UnfilledRowsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    

    public function __construct($message)
    {
        $this->message = $message;
    }

    // Specify the channels (database and broadcast)
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    // Save a record in the notifications table
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }

    // This data is broadcast to Pusher
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message,
        ]);
    }

    // Optionally, if you use a custom broadcast type:
    public function broadcastType()
    {
        return 'unfilled.rows.reminder';
    }
}
