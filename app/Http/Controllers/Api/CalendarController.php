<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Event;
use App\Models\MechanicInfo;
use App\Models\User;
use App\Notifications\newAppointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CalendarController extends Controller
{
    public function events($id)
    {
        $mechanic = MechanicInfo::find($id);

        // Carica gli eventi con la relazione 'mechanics' e 'users' (solo l'email)
        $events = $mechanic->events()->with('mechanics.user:id,email')->get();

        // Aggiungi l'email di ogni mechanic associato ad ogni evento
        $events->transform(function ($event) {
            // Aggiungi un array di email per ogni mechanic associato all'evento
            $event->emails = $event->mechanics->map(function ($mechanic) {
                return $mechanic->user->email;
            });

            // Rimuovi la relazione 'mechanics' per non appesantire la risposta
            unset($event->mechanics);

            return $event;
        });


        return response()->json(['events' => $events, 'availabilities' => $mechanic->availabilities]);
    }

    public function updateAvailability(Request $request){
        $av = Availability::find($request->id);
        if ($av) {
            $state = $request->state == 'Disponibile' ? 'available' : 'not_available';

            $av->update(['state' => $state]);
            return response()->json($av);
        }
        $state = $request->state == 'Disponibile' ? 'available' : 'not_available';

        $av = Availability::create([
            'mechanic_info_id' => $request->mechanic_id,
            'date' => $request->date,
            'state' => $state
        ]);
        return response()->json($av);
    }

    public function updateAvailabilityRange(Request $request) {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $state = $request->state == 'Disponibile' ? 'available' : 'not_available';

        $mechanicId = $request->mechanic_id;
        $array = [];

        // Itera su tutte le date nell'intervallo
        $dates = [];
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->toDateString();

            // Trova o crea la disponibilità
            $availability = Availability::firstOrNew([
                'mechanic_info_id' => $mechanicId,
                'date' => $date->toDateString(),

            ]);
            $availability->state = $state;
            $availability->save();
            array_push($array, $availability);
        }

        return response()->json($array);
    }


    public function createEvent(Request $request){
        $event = Event::create([
            'name' => $request->name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->start_time,
            'notify_me' => $request->notifyMe ?? false
        ]);

        if (!empty($request->selectedMechanics)) {
            $event->mechanics()->attach($request->selectedMechanics);
        }

        $relativeUserIds = [];

        foreach ($request->selectedMechanics as $mechanicId) {
            $mechanic = MechanicInfo::find($mechanicId);
            if ($mechanic && $mechanic->user) {
                $relativeUserIds[] = $mechanic->user->id;
            }
        }

        $admin = User::role('admin')->get();
        $relativeUserIds[] = $admin[0]->id;

        $creator = User::find($request->user_id);
        $fullName = $creator->name . ' ' . $creator->surname;

        $users = User::whereIn('id', $relativeUserIds)
        ->whereHas('notificationPreferences', function ($query) {
            $query->where('new_appointment', true);
        })
        ->get();

        Notification::send($users, new newAppointment($fullName));

        return response()->json($event);
    }
}
