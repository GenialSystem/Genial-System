<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreate extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $user;
    public function __construct($user)
    {
        $this->user = $user;
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

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Operatrice ' . $this->user,
            'message' => 'Ti ha assegnato una nuova commessa',
        ];
    }
    
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Operatrice ' . $this->user,
            'message' => 'Ti ha assegnato una nuova commessa',
            'created_at' => now()->toISOString()
        ]);
    }
}
