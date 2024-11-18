<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\MechanicInfo;
use App\Models\User;
use App\Notifications\MechanicUpdateDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $mechanic_id = Auth::user()->mechanicInfo->id;

        // Cancella tutte le disponibilità più vecchie di un mese
        // Availability::where('mechanic_info_id', $mechanic_id)
        // ->where('date', '<', now()->subMonth()->format('Y-m-d'))
        // ->delete();

        Availability::updateOrCreate(
            ['mechanic_info_id' => $mechanic_id, 'date' => $request->date],
            ['state' => $request->state]
        );
        $admin = User::role('admin')->get();
        $admin[0]->notify(new MechanicUpdateDay(Auth::user()->getFullName())); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Availability $availability)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Availability $availability)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Availability $availability)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Availability $availability)
    {
        //
    }
}
