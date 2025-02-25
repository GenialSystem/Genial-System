<?php

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('notify:upcoming-events', function () {
    $currentDateTime = Carbon::now();
    $today = Carbon::today()->format('Y-m-d');
    
    Log::info('Current DateTime: ' . $currentDateTime->toDateTimeString());
    Log::info('Today: ' . $today);

    // Retrieve today's events with notifications enabled
    $events = Event::where('notify_me', true)
        ->where('date', $today)
        ->get();

    Log::info('Events found: ' . $events->count());

    foreach ($events as $event) {
        $eventStartTime = Carbon::createFromFormat('H:i:s', $event->start_time);
        $notificationTime = $eventStartTime->copy()->subMinutes(10);

        Log::info("Event Start Time: {$eventStartTime->toDateTimeString()}");
        Log::info("Notification Time: {$notificationTime->toDateTimeString()}");
        Log::info($currentDateTime->format('Y-m-d H:i') === $notificationTime->format('Y-m-d H:i'));
        Log::info($currentDateTime->format('Y-m-d H:i'));
        Log::info($notificationTime->format('Y-m-d H:i'));
        // Check if it's time to send notifications
        if ($currentDateTime->format('Y-m-d H:i') === $notificationTime->format('Y-m-d H:i')) {
            foreach ($event->mechanics as $mechanic) {
                $user = $mechanic->user; // Get the related user
            
                if ($user) {
                    // Controlla la preferenza dell'utente per notifiche sugli eventi
                    $preference = $user->notificationPreferences;
            
                    if ($preference && $preference->new_appointment) { // Assumi che new_appointment sia la colonna per gli eventi
                        Log::info("Sending notification for event: {$event->id} to user: {$user->id}");
                        $user->notify(new EventNotification($event));
                    }
                } else {
                    Log::info("No user found for mechanic: {$mechanic->id}");
                }
            }
            
        } else {
            Log::info("Not sending notification for event: {$event->id} - conditions not met.");
        }
    }
})->purpose('Notificare gli eventi imminenti')->everyMinute();

Artisan::command('clear:notifications', function () {
    $deletedRows = DB::table('notifications')->truncate();
    Log::info("Tutte le notifiche sono state eliminate. Numero di notifiche rimosse: $deletedRows");
})->purpose('Eliminare tutte le notifiche ogni settimana')->weekly();