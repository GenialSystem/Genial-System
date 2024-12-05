<?php

namespace App\Livewire;

use App\Models\GeneralDoc;
use App\Models\OrderFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class GeneralDocsModal extends ModalComponent
{

    use WithFileUploads;

    public $name;          // This will hold a single name for each file
    public $files = [];    // Array to hold multiple files
    public $modelId;
    public $isOrderModel;

   
    

    public function submit()
    {
        try {
            // $this->validate();
            DB::beginTransaction();
    
            $newDocs = [];
    
            foreach ($this->files as $file) {
                Log::info('Processing file: ' . $file->getClientOriginalName());
                // Get the original file name
                $originalName = $file->getClientOriginalName();
    
                // Optionally: sanitize the file name to avoid issues (e.g., remove spaces or special characters)
                $safeName = time() . '-' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $originalName);
                if ($this->isOrderModel === 1) {
                    // Store the file with the original name (or sanitized version)
                    $filePath = $file->storeAs('orders/' . $this->modelId . '/docs', $safeName, 'public');
                  
    
                    // Create a new document record in the database
                   
                    $newDoc = OrderFile::create([
                        'order_id' => $this->modelId,
                        'name' => $originalName,
                        'file_path' => $filePath,  // Save the path (with original name) in storage
                    ]);
    
                    $newDocs[] = $newDoc;
                } else {
                    // Store the file with the original name (or sanitized version)
                    $filePath = $file->storeAs('docs/' . $this->modelId, $safeName, 'public');
                    // Create a new document record in the database
                    $newDoc = GeneralDoc::create([
                        'user_id' => $this->modelId,
                        'name' => $originalName,  // Save the original name in the database
                        'file_path' => $filePath,  // Save the path (with original name) in storage
                    ]);
        
                    $newDocs[] = $newDoc;
                }
            }
    
            DB::commit();
    
            // Reset and dispatch events as before
            $this->reset(['files']);
            $this->dispatch('closeModal');
    
            foreach ($newDocs as $doc) {
                $this->dispatch('docAdded', $doc->id);
            }
            Log::info('File upload completed successfully.');
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
