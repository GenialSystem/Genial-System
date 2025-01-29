<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MechanicInfo;
use App\Models\Order;
use App\Models\OrderFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use ZipArchive;

class OrderController extends Controller
{
    public function orders($id, $year)
    {
        $mechanic = MechanicInfo::find($id);

        if ($year) {
            $orders = Order::join('order_mechanic', 'orders.id', '=', 'order_mechanic.order_id')
            ->select('orders.*', 'order_mechanic.mechanic_id as pivot_mechanic_id', 'order_mechanic.order_id as pivot_order_id')
            ->where('order_mechanic.mechanic_id', $id)
            ->whereYear('orders.created_at', $year)
            ->get();
        
        } else {
            // Se non c'è un anno, prendi tutti gli ordini
            $orders = $mechanic->orders;
        }

        // Modifica la struttura per evitare null e salva nome e cognome
        $orders->map(function ($order) {
            if ($order->customer && $order->customer->user) {
                // Salva nome e cognome del cliente
                $user_name = $order->customer->user->name;
                $user_surname = $order->customer->user->surname;

                // Rimuovi la chiave customer
                unset($order->customer);

                // Crea una nuova chiave "customer" con nome e cognome
                $order->customer = [
                    'name' => $user_name,
                    'surname' => $user_surname
                ];
            }
            return $order;
        });
       
        return response()->json([
            'orders' => $orders,
            'repaired_count' => $mechanic->repaired_count,
            'unread_notifications' => count($mechanic->user->unreadNotifications),
        ]);
    }



    public function orderCarParts($id){
        $carParts = Order::find($id)->carParts;
        return response()->json($carParts);
    }

    public function orderImages($id, $disassembly){
        $images = Order::find($id)->images->where('disassembly', $disassembly)->values();
        return response()->json($images);
    }

    public function orderFiles($id){
        $files = Order::find($id)->files;
        return response()->json($files);
    }

    public function update(Request $request, $id)
    {
        try {
            //code...
            $order = Order::findOrFail($id);
    
            $order->update([
                'car_size' => $request->car_size,
                'aluminium' => $request->aluminium,
                'replacements' => $request->replacements,
                'notes' => $request->notes,
                'damage_diameter' => $request->damage_diameter,
            ]);
            $order->customer;
            // Update the pivot table for parts
            foreach ($request->parts as $part) {
                $order->carParts()->syncWithoutDetaching([$part['id'] => [
                    'damage_count' => $part['damage_count'], 
                ]]);
            }
    
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error',
                'order' => $e->getMessage(),
            ]);
        }
    }

    public function uploadImages(Request $request, $id, $type){
     
        // Validate the incoming request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:102400', // 100MB
        ]);
        $order = Order::find($id);
        // Get the uploaded file
        $image = $request->file('image');

        // Generate a unique name for the file
        $imageName = $image->getClientOriginalName();
        $folder = $type == 1 ? 'disassembly' : 'normal'; 
        // Store the image in the specific order folder
        $imagePath = $image->storeAs("orders/{$id}/{$folder}", $imageName, 'public');
        $order->images()->create([
            'image_path' => $imagePath,
            'disassembly' => $type // Mark as normal image
        ]);
        // Return response
        return response()->json($order->images);
    
    }

    public function uploadFiles(Request $request, $id)
    {
        try {
            // Valida l'input
            $request->validate([
                'file' => 'required|file|max:10240', // Max 10 MB
            ]);
    
            // Recupera il file dalla richiesta
            $file = $request->file('file');
    
            // Genera un nome sicuro per il file
            $originalName = $file->getClientOriginalName();
            $sanitizedFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
    
            // Salva il file nella directory specificata
            $filePath = $file->storeAs(
                'orders/' . $id . '/docs', // Directory per gli ordini
                $sanitizedFileName, 
                'public'
            );
    
            // Crea un record nel database
            $orderFile = OrderFile::create([
                'order_id' => $id,
                'name' => $originalName, // Nome originale del file
                'file_path' => $filePath, // Percorso file
            ]);
    
            return response()->json($orderFile, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Errore durante il caricamento del file',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateCarPart(Request $request)
    {
        // Fetch the specific record first.
        $record = DB::table('order_car_part')
            ->where('order_id', $request->order_id)
            ->where('car_part_id', $request->part_id)
            ->first();
    
        if ($record) {
            // Prepare an update array dynamically.
            $updateData = [];
    
            if ($request->has('replacement')) {
                $updateData['replacement'] = $request->replacement;
            } elseif ($request->has('paint_prep')) {
                $updateData['paint_prep'] = $request->paint_prep;
            }
    
            if (!empty($updateData)) {
                // Perform the update in the database.
                DB::table('order_car_part')
                    ->where('order_id', $request->order_id)
                    ->where('car_part_id', $request->part_id)
                    ->update($updateData);
    
                return response()->json(['success' => 'Record updated successfully']);
            } else {
                return response()->json(['error' => 'No valid fields to update'], 400);
            }
        }
    
        return response()->json(['error' => 'Record not found'], 404);
    }
    

}
