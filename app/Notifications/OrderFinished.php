<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderFinished extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $user;
    protected $orderId;
    public function __construct($user, $orderId)
    {
        $this->user = $user;
        $this->orderId = $orderId;
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
            'message' => 'Ha terminato la commessa #' . $this->orderId,
        ];
    }
    
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Operatrice ' . $this->user,
            'message' => 'Ha terminato la commessa #' . $this->orderId,
            'created_at' => now()->toISOString()
        ]);
    }
}
