<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMechanicInfoRequest;
use App\Models\CustomerInfo;
use App\Models\MechanicInfo;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MechanicInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mechanics.index');
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
    public function store(StoreMechanicInfoRequest $request)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            $validatedData = $request->validated();
            // Create a new User instance
            $newUser = User::create([
                'name' => $validatedData['name'],
                'surname' => $validatedData['surname'],
                'email' => $validatedData['email'],
                'password' => Hash::make('password'),
            ]);

            $newUser->assignRole('mechanic');
            
            // Create a new MechanicInfo instance
            $mechanicInfo = new MechanicInfo([
                'user_id' => $newUser->id,
                'cellphone' => $validatedData['cellphone'],
                'cdf' => $validatedData['cdf'],
                'address' => $validatedData['address'],
                'city' => $validatedData['city'],
                'province' => $validatedData['province'],
                'branch' => $validatedData['branch'],
                'cap' => $validatedData['cap'],
                'repaired_count' => $validatedData['repaired_count'] ?? 0,
                'working_count' => $validatedData['working_count'] ?? 0,
            ]);
            $mechanicInfo->save();

            DB::commit(); // Commit the transaction

            // Redirect with success message
            return redirect()->route('mechanics.index')->with('success', ['title' => 'Nuovo tecnico creato', 'subtitle' => 'Il tecnico è stato aggiunto con successo.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction if there is an error

            // Log the error for debugging
            Log::error('Error creating mechanic info: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()
                ->with('error', ['title' => 'Errore', 'subtitle' => 'Qualcosa è andato storto. Riprova. ' . $e->getMessage()])
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(MechanicInfo $mechanic)
    {
        return view('mechanics.show', compact('mechanic'));
    }

    public function calendar(MechanicInfo $mechanic)
    {
        // Load the mechanic's events with the pivot data (confirmed, client_name)
        $events = $mechanic->events()->withPivot('confirmed', 'client_name')->get()->map(function($event) use ($mechanic) {
            return [
                'id' => $event->id,
                'title' => $event->name,
                'start' => $event->date . 'T' . $event->start_time,
                'end' => $event->date . 'T' . $event->end_time,
                'mechanics' => [
                    [
                        'id' => $mechanic->id,
                        'surname' => $mechanic->surname,
                        'confirmed' => $event->pivot->confirmed,  // Get confirmed status from pivot
                        'client_name' => $event->pivot->client_name,  // Get client_name from pivot
                    ]
                ],
            ];
        });
        $customers = CustomerInfo::with('user')->get();
        $availabilities = $mechanic->availabilities;
        return view('mechanics.calendar', compact('mechanic', 'events', 'customers', 'availabilities'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MechanicInfo $mechanicInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MechanicInfo $mechanicInfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MechanicInfo $mechanicInfo)
    {
        //
    }
}
