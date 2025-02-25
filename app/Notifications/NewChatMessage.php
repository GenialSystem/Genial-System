<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Facades\Log;
class NewChatMessage extends Notification
{
    use Queueable;

    protected $chatMessage;

    public function __construct($chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $notifiable->name . ' ' . $notifiable->surname . ' ti ha inviato un nuovo messaggio: ',
            'message' => $this->chatMessage,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {

        return new BroadcastMessage([
            'title' =>  $notifiable->name . ' ' . $notifiable->surname . ' ti ha inviato un nuovo messaggio: ',
            'message' => $this->chatMessage ,
            'created_at' => now()->toISOString()
        ]);
    }
}
