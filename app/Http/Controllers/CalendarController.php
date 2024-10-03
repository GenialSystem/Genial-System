<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\CustomerInfo;
use App\Models\Event;
use App\Models\MechanicInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load events with mechanics and the pivot data (confirmed, client_name)
        $events = Event::with(['mechanics' => function($query) {
            $query->withPivot('confirmed', 'client_name'); // Get pivot data
        }])->get()->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->name,
                'start' => $event->date . 'T' . $event->start_time,
                'end' => $event->date . 'T' . $event->end_time,
                'mechanics' => $event->mechanics->map(function($mechanic) {
                    return [
                        'id' => $mechanic->id,
                        'surname' => $mechanic->surname,
                        'confirmed' => $mechanic->pivot->confirmed,  // Get confirmed status from pivot
                        'client_name' => $mechanic->pivot->client_name,  // Get client_name from pivot
                    ];
                }),
            ];
        });
    
        // Load mechanics and customers
        $mechanics = MechanicInfo::take(10)->get();
        $customers = CustomerInfo::all();
    
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
                    'client_name' => $customer->name, // Update the client_name field with the customer's name
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
