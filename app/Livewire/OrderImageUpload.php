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
    
    // Define the total upload size limit (in bytes)
    private $maxTotalSize = 10 * 1024 * 1024; // 10MB in bytes

    protected $rules = [
        'normalImages.*' => 'image|max:2048', // Max 2MB per image
        'disassemblyImages.*' => 'image|max:2048', // Max 2MB per image
    ];

    // Method to check total size
    private function validateTotalSize($images)
    {
        $totalSize = 0;
        foreach ($images as $image) {
            $totalSize += $image->getSize();
        }

        // Check if total size exceeds 10MB
        if ($totalSize > $this->maxTotalSize) {
            return false;
        }
        return true;
    }

    public function updatedNormalImages()
    {
        // Validate each image individually
        $this->validate();

        // Check total size of the images
        if (!$this->validateTotalSize($this->normalImages)) {
            $this->addError('normalImages', 'The total size of normal images cannot exceed 10MB.');
            return;
        }

        // Process and store each image
        foreach ($this->normalImages as $image) {
            // Store the image in /orders/{order->id}/normal
            $path = $image->storeAs('orders/' . $this->orderId . '/normal', $image->getClientOriginalName());

            // Save the image record in the database
            OrderImage::create([
                'order_id' => $this->orderId,
                'image_path' => $path,
            ]);
        }

        // Reset the uploaded images and provide success feedback
        $this->normalImages = [];
    }

    public function updatedDisassemblyImages()
    {
        // Validate each image individually
        $this->validate();

        // Check total size of the images
        if (!$this->validateTotalSize($this->disassemblyImages)) {
            $this->addError('disassemblyImages', 'The total size of disassembly images cannot exceed 10MB.');
            return;
        }

        // Process and store each image
        foreach ($this->disassemblyImages as $image) {
            // Store the image in /orders/{order->id}/disassembly
            $path = $image->storeAs('orders/' . $this->orderId . '/disassembly', $image->getClientOriginalName());

            // Save the image record in the database, marking it as a disassembly image
            OrderImage::create([
                'order_id' => $this->orderId,
                'image_path' => $path,
                'disassembly' => true, // Disassembly images are marked as true
            ]);
        }

        // Reset the uploaded images and provide success feedback
        $this->disassemblyImages = [];
    }

    public function render()
    {
        return view('livewire.order-image-upload');
    }
}
