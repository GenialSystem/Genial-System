<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Office; // Import the Office model
use Illuminate\Support\Facades\Storage;
use LivewireUI\Modal\ModalComponent;

class OfficeRenameItem extends ModalComponent
{
    public $itemId; // To store the ID of the item being renamed
    public $type; // To differentiate between file and folder
    public $name; // New name for the item

    // Mount method to initialize properties
    public function mount($itemId, $type)
    {
        $this->itemId = $itemId; // Set the item ID
        $this->type = $type; // Set the type (file or folder)

        // Load the current name of the item to be renamed
        $item = Office::find($this->itemId);
        $this->name = $item ? $item->name : ''; // Set the current name
    }

    public function submit()
    {
        // Validate the new name
        $this->validate([
            'name' => 'required|string|max:255',
        ]);
    
        // Find the item (file or folder) to rename
        $item = Office::find($this->itemId);
    
        if (!$item) {
            session()->flash('error', 'Item not found');
            return;
        }
    
        // Check if a file or folder with the same name already exists in the same parent folder
        $existingItem = Office::where('parent_id', $item->parent_id)
                              ->where('name', $this->name)
                              ->where('type', $this->type)
                              ->first();
    
        if ($existingItem) {
            session()->flash('error', 'An item with this name already exists in the folder.');
            return;
        }
    
        // Handle renaming logic
        $oldPath = $item->path;
    
        // If it's a file, preserve the file extension
        if ($this->type === 'file') {
            $fileExtension = pathinfo($item->name, PATHINFO_EXTENSION); // Get the current file extension
            $newFileName = pathinfo($this->name, PATHINFO_FILENAME); // Get the new name without extension
            $newNameWithExtension = $newFileName . '.' . $fileExtension; // Append the original extension
    
            $newPath = 'offices/' . ($item->parent_id ? $item->parent->name . '/' : '') . $newNameWithExtension;
    
            // Ensure the file exists before renaming
            if (Storage::exists($oldPath)) {
                Storage::move($oldPath, $newPath);  // Move the file
                $item->path = $newPath;  // Update the path in the database
            } else {
                session()->flash('error', 'File not found in storage');
                return;
            }
        } elseif ($this->type === 'folder') {
            // Rename folder logic
            $newPath = 'offices/' . ($item->parent_id ? $item->parent->name . '/' : '') . $this->name;
    
            // Check if folder exists before renaming
            if (Storage::exists($oldPath)) {
                // Move the folder in storage
                Storage::move($oldPath, $newPath);
                $item->path = $newPath;  // Update the folder's path in the database
                
                // Update the paths of all child files and folders
                Office::where('parent_id', $item->id)->get()->each(function($child) use ($oldPath, $newPath) {
                    // Build the new path for the child
                    $childNewPath = str_replace($oldPath, $newPath, $child->path);
                    // Move the child's file or folder in the storage
                    if (Storage::exists($child->path)) {
                        Storage::move($child->path, $childNewPath);
                    }
                    // Update the child's path in the database
                    $child->path = $childNewPath;
                    $child->save();
                });
            } else {
                session()->flash('error', 'Folder not found in storage');
                return;
            }
        }
    
        // Finally, update the name in the database
        $item->name = $this->type === 'file' ? $newNameWithExtension : $this->name;
        $item->save();
    
        // Close the modal and refresh the page
        $this->dispatch('closeModal');
        $this->dispatch('refreshPage');
    }
    


    public function render()
    {
        return view('livewire.office-rename-item');
    }
}
