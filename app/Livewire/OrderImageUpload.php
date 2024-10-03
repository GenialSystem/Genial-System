<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\OrderImage;

class OrderImageUpload extends Component
{
    use WithFileUploads;

    public $orderId;
    public $normalImages = [];
    public $disassemblyImages = [];

    protected $rules = [
        'normalImages.*' => 'image|max:2048', // Max 2MB per image
        'disassemblyImages.*' => 'image|max:2048', // Max 2MB per image
    ];

    public function updatedNormalImages()
    {
        // Validate the images
        $this->validate();

        // Process each image
        foreach ($this->normalImages as $image) {
            // Store the image in /orders/{order->id}/normal
            $path = $image->storeAs('orders/' . $this->orderId . '/normal', $image->getClientOriginalName());

            // Create a record in the database
            OrderImage::create([
                'order_id' => $this->orderId,
                'image_path' => $path,
            ]);
        }
        // Reset the updateded images and provide success feedback
        $this->normalImages = [];
        session()->flash('normal_success', 'Normal images updateded successfully!');
    }

    public function updatedDisassemblyImages()
    {
        // Validate the images
        $this->validate();

        // Process each image
        foreach ($this->disassemblyImages as $image) {
            // Store the image in /orders/{order->id}/disassembly
            $path = $image->storeAs('orders/' . $this->orderId . '/disassembly', $image->getClientOriginalName());

            // Create a record in the database
            OrderImage::create([
                'order_id' => $this->orderId,
                'image_path' => $path,
                'disassembly' => true, // Disassembly images are marked as true
            ]);
        }

        // Reset the uploaded images and provide success feedback
        $this->disassemblyImages = [];
        session()->flash('disassembly_success', 'Disassembly images uploaded successfully!');
    }

    public function render()
    {
        return view('livewire.order-image-upload');
    }
}
