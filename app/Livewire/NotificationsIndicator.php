<?php

// app/Livewire/NotificationsIndicator.php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationsIndicator extends Component
{
    public $hasUnreadNotifications = false;

    public function mount()
    {
        $this->updateNotificationStatus();
    }

    public function updateNotificationStatus()
    {
        $this->hasUnreadNotifications = Auth::user()
            ->unreadNotifications()
            ->exists();
    }

    #[On('notification-received')]
    public function handleNewNotification()
    {
        $this->updateNotificationStatus();
    }

    #[On('notifications-read')]
    public function handleNotificationsRead()
    {
        $this->updateNotificationStatus();
    }

    public function render()
    {
        return view('livewire.notifications-indicator');
    }
}
