<?php
namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class DocumentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        $url = url('/documents/' . $this->document->id);
        
        return (new MailMessage)
                    ->subject('Document Status Update')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('This is to notify you that your document status has been updated.')
                    ->line('You are receiving this email because you are a registered user of our system.')
                    ->action('View Document', $url)
                    ->line('If you have any questions, please contact support.')
                    ->line('Thank you for using our application!')
                    ->salutation('Best regards,')
                    ->salutation(config('app.name'));
    }
    
    public function toArray($notifiable)
    {
        return [
            'tracking_number' => $this->document->tracking_number,
            'status'          => $this->document->status,
            'message'         => 'Document status updated to ' . $this->document->status,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'tracking_number' => $this->document->tracking_number,
            'status'          => $this->document->status,
            'message'         => 'Document status updated to ' . $this->document->status,
        ]);
    }

    // Do NOT include a broadcastAs() method so that the default event name is used.
    public function broadcastAs()
    {
        return 'document.status.updated';
    }
}
