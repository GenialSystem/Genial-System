<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LivewireUI\Modal\ModalComponent;

class OrderUploadModal extends ModalComponent
{
    use WithFileUploads;

    public $type; // Normal or disassembly
    public ?Order $order = null; // Make order nullable and initialize it
    public $images = []; // For uploaded images

    // Use the mount method to initialize order and other variables
    public function mount(Order $order, $type)
    {
        $this->order = $order;
        $this->type = $type;
    }
    
    public static function modalMaxWidth(): string
    {
        return '4xl';
    }

    protected $listeners = ['fileInputUpdated', 'removeFileFromImages'];

    public function fileInputUpdated()
    {
        $this->images = array_filter($this->images, function ($image) {
            return $image !== null;
        });
    }

    public function removeFileFromImages($index)
    {
        // Remove the file at the given index from the images array
        unset($this->images[$index]);

        // Reindex the array to avoid gaps
        $this->images = array_values($this->images);
    }

    public function uploadImages()
    {
        try {
            // Check if images are uploaded
            $hasSkippedImages = []; // Array to store names of skipped images
            if (empty($this->images)) {
                $this->dispatch('closeModal');
                return;
            }
    
            // Determine the folder based on the type
            $folder = $this->type === 'disassembly' ? 'disassembly' : 'normal';
    
            foreach ($this->images as $image) {
                $imageName = $image->getClientOriginalName();
                $imagePath = "orders/{$this->order->id}/{$folder}/{$imageName}";
    
                // 1. Check if the file already exists in storage
                if (Storage::disk('public')->exists($imagePath)) {
                    // 2. Check if there's already an entry in the database for this file
                    $existingImage = $this->order->images()->where('image_path', $imagePath)->first();
    
                    if ($existingImage) {
                        // If the file and the record both exist, skip this file
                        $hasSkippedImages[] = $imageName; // Add skipped image name to the array
                        continue;
                    }
                } else {
                    // If the file doesn't exist in storage, store it
                    $imagePath = $image->storeAs("orders/{$this->order->id}/{$folder}", $imageName, 'public');
                }
    
                // 3. Create a record in the `order_images` table only if not already present
                $this->order->images()->create([
                    'image_path' => $imagePath,
                    'disassembly' => $this->type === 'disassembly', // Flag if disassembly image
                ]);
            }
    
            // Close the modal after upload
            $this->dispatch('closeModal');
    
            // Prepare the flash message
            $message = count($hasSkippedImages) ? 
                'Alcune immagini non sono state caricate, prova a cambiare il nome dei seguenti files: ' . implode(', ', $hasSkippedImages) : 
                'Immagini caricate con successo';
    
            // Redirect with success message
            return redirect()->route('orders.show', $this->order->id)->with($hasSkippedImages ? 'warning' : 'success', [
                'title' => 'Operazione confermata.', 
                'subtitle' => $message,
            ]);
        } catch (\Exception $e) {
            // Handle error case
            Log::error($e->getMessage());
            return redirect()->route('orders.show', $this->order->id)->with('error', [
                'title' => 'Qualcosa è andato storto.', 
                'subtitle' => $e->getMessage(),
            ]);
        }
    }
    


    public function render()
    {
        return view('livewire.order-upload-modal');
    }
}
