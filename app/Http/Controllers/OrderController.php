<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\CarPart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('orders.index');
    }

    public function done_orders()
    {
        return view('orders.done_orders');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('orders.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();
        // dd($request);
        try {
            // $request->validate();
            // 1. Create the order
            $order = Order::create([
                'date' => $request->input('date'),
                'state' => 'Nuova',
                'customer_id' => $request->input('customer'),
                'admin_name' => $request->input('admin_name'),
                'earn_mechanic_percentage' => $request->input('earn_mechanic_percentage'),
                'plate' => $request->input('plate'),
                'brand' => $request->input('brand'),
                'price' => $request->input('price'),
                'car_size' => $request->input('car_size'),
                'aluminium' => $request->input('aluminium') == 'on' ? true : false,
                'assembly_disassembly' => false,
                'damage_diameter' => '25 - 50mm',
                'replacements' => $request->input('replacements'),
                'notes' => $request->input('replacements')
            ]);
            
            // 2. Attach mechanics (assuming a pivot table exists for this relation)
            $order->mechanics()->attach($request->input('mechanic'));
            
            // 3. Attach car parts damage to pivot table `order_car_part`
            foreach ($request->input('parts') as $part) {
                $carPart = CarPart::where('name', $part['name'])->first();
            
                if ($carPart) {
                    // Attach the part to the order using the correct field names
                    $order->carParts()->attach($carPart->id, [
                        'damage_count' => $part['damage_count'], 
                        'paint_prep' => false,  // Correct field name
                        'replacement' => false // Correct field name
                    ]);
                }
            }
            


            DB::commit();

            return redirect()->route('orders.index')->with('success', [
                'title' => 'Nuova riparazione creata',
                'subtitle' => 'La riparazione da te creata è ora inserita nella sezione delle riparazioni',
            ]);

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollback();
            return redirect()->back()->withErrors('Error creating order: ' . $e->getMessage());
        }
      return response()->json($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $parts = CarPart::all();
        $car_sizes = ['Piccolo', 'Medio', 'Grande', 'Veicolo commerciale'];
        return view('orders.show', compact('order', 'parts', 'car_sizes'));
    }

    public function showDoneOrder(Order $order)
    {
        $parts = CarPart::all();
        $car_sizes = ['Piccolo', 'Medio', 'Grande', 'Veicolo commerciale'];
        return view('orders.show_done_order', compact('order', 'parts', 'car_sizes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // 1. Find the existing order
            $order = Order::findOrFail($id);

            // 2. Update the order details
            $order->update([
                'car_size' => $request->car_size,
                'aluminium' => $request->aluminium == 'on' ? true : false,
                'replacements' => $request->replacements,
                'notes' => $request->notes,
            ]);
            // dd($order);
            // 3. Sync car parts damage to the pivot table `order_car_part`
            foreach ($request->input('parts') as $part) {
                $carPart = CarPart::where('name', $part['name'])->first();

                if ($carPart) {
                    // Sync the part to the order using the correct field names
                    $order->carParts()->syncWithoutDetaching([$carPart->id => [
                        'damage_count' => $part['damage_count'], 
                        // 'paint_prep' => false,  // Update this if needed
                        // 'replacement' => false // Update this if needed
                    ]]);
                }
            }

            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', [
                'title' => 'Riparazione aggiornata',
                'subtitle' => 'La riparazione è stata aggiornata con successo.',
            ]);

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollback();
            return redirect()->back()->with('error', ['title'=> 'Errore durante l\'aggiornamento della commmessa', 'subtitle' => $e->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
