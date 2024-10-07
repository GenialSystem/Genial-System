<?php

namespace App\Livewire;

use App\Models\GeneralDoc;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class GeneralDocsModal extends ModalComponent
{

    use WithFileUploads;

    public $name;          // This will hold a single name for each file
    public $files = [];    // Array to hold multiple files
    public $userId;

    protected $rules = [
        'files.*' => 'required|file|max:10240', // Validate each file, max size 10MB
    ];

    public function submit()
    {
        try {
            $this->validate();
    
            DB::beginTransaction();
    
            $newDocs = [];
    
            foreach ($this->files as $file) {
                // Get the original file name
                $originalName = $file->getClientOriginalName();
    
                // Optionally: sanitize the file name to avoid issues (e.g., remove spaces or special characters)
                $safeName = time() . '-' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $originalName);
    
                // Store the file with the original name (or sanitized version)
                $filePath = $file->storeAs('docs/' . $this->userId, $safeName, 'public');
    
                // Create a new document record in the database
                $newDoc = GeneralDoc::create([
                    'user_id' => $this->userId,
                    'name' => $originalName,  // Save the original name in the database
                    'file_path' => $filePath,  // Save the path (with original name) in storage
                ]);
    
                $newDocs[] = $newDoc;
            }
    
            DB::commit();
    
            // Reset and dispatch events as before
            $this->reset(['files']);
            $this->dispatch('closeModal');
    
            foreach ($newDocs as $doc) {
                $this->dispatch('docAdded', $doc->id);
            }
    
            session()->flash('message', 'Documents uploaded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back();
        }
    }
    


    public function render()
    {
        return view('livewire.general-docs-modal');
    }
}
