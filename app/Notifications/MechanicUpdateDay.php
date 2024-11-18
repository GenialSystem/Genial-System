<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MechanicUpdateDay extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $mechanic;
    public function __construct($mechanic)
    {
        $this->mechanic = $mechanic;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Modifica disponibilità',
            'message' => $this->mechanic . ' ha modificato le sue disponibilità',
        ];
    }
    
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Modifica disponibilità',
            'message' => $this->mechanic . ' ha modificato le sue disponibilità',
            'created_at' => now()->toISOString()
        ]);
    }
}
