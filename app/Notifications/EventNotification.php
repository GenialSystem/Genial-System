<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventNotification extends Notification
{
    use Queueable;

    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the array representation of the notification for the database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Promemoria evento: ' . $this->event->name,
            'message' => 'L\'evento "' . $this->event->name . '" inizierà tra 10 minuti!',
            'start_time' => $this->event->start_time,
            'date' => $this->event->date,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Promemoria evento: ' . $this->event->name,
            'message' => 'L\'evento "' . $this->event->name . '" inizierà tra 10 minuti!',
            'created_at' => now()->toISOString()
        ]);
    }
}
