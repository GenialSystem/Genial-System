<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;
use Livewire\Component;

class Notifications extends Component
{
    public $notifications = [];
    public $id;
    public $groupedNotifications = [];
    protected $listeners = ['addNotification'];
    public $isSidebarOpen = false;

    public function mount()
    {
        // Load user notifications into an array
        $this->notifications = Auth::user()->notifications->toArray();
        $this->id = Auth::user()->id;

        // Group notifications by date
        $this->groupNotificationsByDate();
    }

    public function toggleSidebar()
    {
        $this->isSidebarOpen = !$this->isSidebarOpen;
    }

    public function closeSidebar()
    {
        $this->isSidebarOpen = false;
    }

    // Method to mark notification as read
    public function markAsRead($notificationId)
    {
       $notification = DatabaseNotification::find($notificationId);
       $notification->update(['read_at' => now()]);
        foreach ($this->notifications as &$notification) {
            if ($notification['id'] == $notificationId) {
                $notification['read_at'] = now();
                break;
            }
        }

        // Regroup notifications after marking one as read
        $this->groupNotificationsByDate();
        
    }

    // Group notifications by date and format date
    public function groupNotificationsByDate()
    {
        $this->groupedNotifications = collect($this->notifications)->groupBy(function ($notification) {
            return Carbon::parse($notification['created_at'])->format('Y-m-d');
        })->map(function ($dailyNotifications, $date) {
            $carbonDate = Carbon::parse($date);
            if ($carbonDate->isToday()) {
                $formattedDate = 'Oggi'; // Today
            } elseif ($carbonDate->isYesterday()) {
                $formattedDate = 'Ieri'; // Yesterday
            } else {
                $formattedDate = $carbonDate->format('d/m/Y'); // Date in dd/mm/yyyy format
            }

            return [
                'formatted_date' => $formattedDate,
                'notifications' => $dailyNotifications,
            ];
        });
    }

    public function addNotification($notificationData)
    {
        // Create a new notification array
        $newNotification = [
            'id' => $notificationData['id'], // Assuming you send an ID with your notification
            'data' => [
                'title' => $notificationData['title'],
                'message' => $notificationData['message'],
            ],
            'created_at' => Carbon::parse($notificationData['created_at']),
            'read_at' => null, // Initial unread state
        ];

        // Add to the existing notifications
        array_unshift($this->notifications, $newNotification);

        // Re-group notifications after adding
        $this->groupNotificationsByDate(); 
    }


    public function render()
    {
        return view('livewire.notifications');
    }
}
