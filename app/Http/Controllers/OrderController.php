<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\CarPart;
use App\Models\Chat;
use App\Models\CustomerInfo;
use App\Models\MechanicInfo;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderCreate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Spatie\Browsershot\Browsershot;

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
        try {
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
                'aluminium' => $request->input('aluminium') == 'on',
                'assembly_disassembly' => false,
                'damage_diameter' => $request->input('damage_diameter'),
                'replacements' => $request->input('replacements'),
                'notes' => $request->input('notes')
            ]);
    
            // 2. Attach mechanics
            $mechanicIds = explode(',', $request->input('mechanic'));
            // Attach mechanics to the order
            $order->mechanics()->attach($mechanicIds);

            $mechanicIds = MechanicInfo::whereIn('id', $mechanicIds)
            ->with('user') // Carica la relazione "user" per ogni meccanico
            ->get()
            ->map(function ($mechanic) {
                return $mechanic->user->id; // Estrai l'user_id
            })
            ->filter() // Rimuove eventuali null (se un meccanico non ha un utente associato)
            ->values(); // Resetta le chiavi


            // 3. Attach car parts damage
            foreach ($request->input('parts') as $part) {
                $carPart = CarPart::where('name', $part['name'])->first();
                if ($carPart) {
                    $order->carParts()->attach($carPart->id, [
                        'damage_count' => $part['damage_count'],
                        'paint_prep' => false,
                        'replacement' => false
                    ]);
                }
            }
    
            // 4. Store normal images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    // Store image in "orders/{order_id}/normal" directory
                    $imagePath = $image->storeAs("orders/{$order->id}/normal", $image->getClientOriginalName(), 'public');
    
                    // Create a record in order_images table
                    $order->images()->create([
                        'image_path' => $imagePath,
                        'disassembly' => false // Mark as normal image
                    ]);
                }
            }
    
            // 5. Store disassembly images
            if ($request->hasFile('images-disassembly')) {
                foreach ($request->file('images-disassembly') as $image) {
                    // Store image in "orders/{order_id}/disassembly" directory
                    $imagePath = $image->storeAs("orders/{$order->id}/disassembly", $image->getClientOriginalName(), 'public');
    
                    // Create a record in order_images table with disassembly flag
                    $order->images()->create([
                        'image_path' => $imagePath,
                        'disassembly' => true // Mark as disassembly image
                    ]);
                }
            }
            $chat = Chat::create([
                'type' => 'single',
                'order_id' => $order->id
            ]);
            
            $adminUser = User::role('admin')->first();
            
            $customer = CustomerInfo::find($request->input('customer'));
            
            if ($adminUser && $customer && $customer->user) {
                $userIds = [$adminUser->id, $customer->user->id];
                $chat->users()->attach($userIds);
            }
        
            DB::commit();
            $redirectRoute = Auth::user()->roles->pluck("name")->first() === 'mechanic' ? 'home' : 'orders.index'; 
            $mechanicIds[] = $customer->user->id;
            $users = User::whereIn('id', $mechanicIds)
            ->where(function ($query) {
                $query->whereHas('notificationPreferences', function ($subQuery) {
                    $subQuery->where('new_order_assigned', true);
                })
                ->orWhereDoesntHave('notificationPreferences'); // Utenti senza preferenze
            })
            ->get();
            
            Log::info($mechanicIds);
            Log::info($users);
            $creator = Auth::user();
            
            Notification::send($users, new OrderCreate($creator));
            
            return redirect()->route($redirectRoute)->with('success', [
                'title' => 'Nuova riparazione creata',
                'subtitle' => 'La riparazione da te creata è ora inserita nella sezione delle riparazioni',
            ]);
    
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('Errore durante la creazione: ' . $e->getMessage());
        }
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
                'damage_diameter' => $request->damage_diameter,
                'earn_mechanic_percentage' => $request->earn_mechanic_percentage,
            ]);
           
            // 3. Sync car parts damage to the pivot table `order_car_part`
            foreach ($request->input('parts') as $part) {
                $carPart = CarPart::where('name', $part['name'])->first();

                if ($carPart) {
                    // Sync the part to the order using the correct field names
                    $order->carParts()->syncWithoutDetaching([$carPart->id => [
                        'damage_count' => $part['damage_count'], 
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
