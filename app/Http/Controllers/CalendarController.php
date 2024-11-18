<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Calendar;
use App\Models\CustomerInfo;
use App\Models\Event;
use App\Models\MechanicInfo;
use App\Models\User;
use App\Notifications\MechanicUpdateDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Livewire\memoize;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        $mechanics = [];
        $customers = [];
        $availabilities = [];
        // Check if the user has the 'mechanic' role
        if ($user->hasRole('mechanic')) {
                // If the user is a mechanic, only get their events
            $mechanic = MechanicInfo::where('user_id', $user->id)->first();
        
            $events = $mechanic->events()->with(['mechanics' => function($query) use ($mechanic) {
                $query->where('mechanic_id', $mechanic->id) // Filter mechanics by the logged-in mechanic's ID
                    ->withPivot('confirmed', 'client_name'); // Get pivot data
            }])->get()->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->name,
                    'start' => $event->date . 'T' . $event->start_time,
                    'end' => $event->date . 'T' . $event->end_time,
                    'date' => $event->date, // Ensure date is included
                    'mechanics' => $event->mechanics->map(function($mechanic) {
                        return [
                            'id' => $mechanic->id,
                            'surname' => $mechanic->surname,
                            'confirmed' => $mechanic->pivot->confirmed,
                            'client_name' => $mechanic->pivot->client_name,
                        ];
                    }),
                ];
            });
            $events = $events->toArray();
            $availabilities = $mechanic->availabilities;
            // dd($events);
        } else {
            // If not a mechanic, get all events as usual
            $events = Event::with(['mechanics' => function($query) {
                $query->withPivot('confirmed', 'client_name'); // Get pivot data
            }])->get()->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->name,
                    'start' => $event->date . 'T' . $event->start_time,
                    'end' => $event->date . 'T' . $event->end_time,
                    'date' => $event->date, // Ensure date is included
                    'mechanics' => $event->mechanics->map(function($mechanic) {
                        return [
                            'id' => $mechanic->id,
                            'surname' => $mechanic->surname,
                            'confirmed' => $mechanic->pivot->confirmed,
                            'client_name' => $mechanic->pivot->client_name,
                        ];
                    }),
                ];
            });
            $mechanics = MechanicInfo::all();
            $availabilities = Availability::with('mechanicInfo')->get();
            // dd($availabilities);
            $customers = CustomerInfo::with('user')->get();
        }
    
        return view('schedules.calendar', compact('mechanics', 'events', 'customers', 'availabilities'));
    }

    
    public function updateCustomer(Request $request)
    {
        try {
            $customer = CustomerInfo::findOrFail($request->customer_id); // Restituisce 404 se non trovato

            $updated = DB::table('availabilities')
                ->where('mechanic_info_id', $request->mechanic_id)
                ->where('date', $request->date)
                ->update([
                    'client_name' => $customer->user->name . ' ' . $customer->user->surname,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => $updated,
                'message' => $updated ? 'Disponibilità aggiornata con successo.' : 'Nessuna disponibilità trovata.'
            ], $updated ? 200 : 404);
            
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Errore: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Calendar $calendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Calendar $calendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calendar $calendar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calendar $calendar)
    {
        //
    }
}
