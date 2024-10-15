<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\CustomerInfo;
use App\Models\Event;
use App\Models\MechanicInfo;
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
        $customers = CustomerInfo::with('user')->get();
    }
   
    return view('schedules.calendar', compact('mechanics', 'events', 'customers'));
}


   
    
    public function updateCustomer(Request $request)
    {
        // Validate the request data
        $request->validate([
            'mechanic_id' => 'required|exists:mechanic_infos,id', // Ensure mechanic_id exists in mechanic_infos table
            'event_id' => 'required|exists:events,id', // Ensure event_id exists in events table
            'customer_id' => 'required|exists:customer_infos,id', // Ensure customer_id exists in customers table
        ]);
        // Find the customer's name based on the customer_id
        $customer = CustomerInfo::find($request->customer_id);
        if ($customer) {
            // Update the event_mechanic pivot table
            $updated = DB::table('event_mechanic')
                ->where('mechanic_id', $request->mechanic_id)
                ->where('event_id', $request->event_id)
                ->update([
                    'client_name' => $customer->user->name . ' ' . $customer->user->surname, // Update the client_name field with the customer's name
                    'confirmed' => true, // Optionally set confirmed to true if necessary
                    'updated_at' => now(), // Update timestamp
                ]);
    
            // Check if the update was successful
            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Customer updated successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to update customer'], 400);
            }
        }
    
        // If customer was not found, return an error response
        return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
    }

    public function updateMechanicDay(Request $request)
    {
        try {
           
            $request->validate([
                'confirmed' => 'required',
                'event_id' => 'required'
            ]);
           
            $mechanic = MechanicInfo::where('user_id', Auth::id())->first();
            if ($mechanic) {
                // Update the event_mechanic pivot table
                $updated = DB::table('event_mechanic')
                    ->where('event_id', $request->event_id)
                    ->where('mechanic_id', $mechanic->id)
                    ->update([
                        'confirmed' => $request->confirmed, 
                    ]);
        
                    return response()->json(['success' => true, 'message' => 'mechanic updated successfully']);
                
            }
        
            // If mechanic was not found, return an error response
            return response()->json(['error' => false, 'message' => 'mechanic not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
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
