<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstimateStateChanged extends Notification
{
    use Queueable;

    protected $user;
    protected $estimate;

    public function __construct($user, $estimate)
    {
        $this->user = $user;
        $this->estimate = $estimate;
    }

    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->user,
            'message' => 'Ha modificato lo stato del preventivo ' . $this->estimate->number . ' in ' . $this->estimate->state,
        ];
    }
    
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => $this->user,
            'message' => 'Ha modificato lo stato del preventivo ' . $this->estimate->number . ' in ' . $this->estimate->state,
            'created_at' => now()->toISOString()
        ]);
    }
}
