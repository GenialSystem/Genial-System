<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class EstimateRequest extends Notification 
{

    protected $customer;

    public function __construct($customer)
    {
        $this->customer = $customer;
    }

    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->customer,
            'message' => 'ha inviato una nuova richiesta di preventivo.',
        ];
    }
    
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => $this->customer,
            'message' => 'ha inviato una nuova richiesta di preventivo.',
            'created_at' => now()->toISOString()
        ]);
    }
}
