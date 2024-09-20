<?php

namespace App\Http\Controllers;

use App\Models\Workstation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkstationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('workstations.index');
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
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'customer' => 'required|exists:customer_infos,id',
                'city' => 'required|string|max:255',
                'assigned_cars' => 'required|integer|min:0',
                'admin_name' => 'required|string|max:255',
                'mechanics' => 'required|array',
                'mechanics.*' => 'exists:mechanic_infos,id' 
            ]);
            
            $workstation = Workstation::create([
                'customer_id' => $validatedData['customer'],
                // 'city' => $validatedData['city'],
                // 'assigned_cars' => $validatedData['assigned_cars'],
                // 'admin_name' => $validatedData['admin_name'],
            ]);
        
            // Attach the mechanics to the workstation
            $workstation->mechanics()->attach($validatedData['mechanics']);
            
            DB::commit();
            return redirect()->route('workstations.index')->with('success', [
                        'title' => 'Nuova postazione aggiunta',
                        'subtitle' => 'La postazione che hai creato è stata aggiunta alla sezione ‘Postazioni di lavoro’']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('workstations.index')->with('error', [
                'title' => 'Errore durante la creazione',
                'subtitle' => $th->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Workstation $workstation)
    {
        return view('workstations.show', compact('workstation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workstation $workstation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Workstation $workstation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workstation $workstation)
    {
        //
    }
}
