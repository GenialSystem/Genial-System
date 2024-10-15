<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Office;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LivewireUI\Modal\ModalComponent;

class OfficeUploadModal extends ModalComponent
{
    use WithFileUploads;

    public $type;  // Either 'file' or 'folder'
    public $file;  // Uploaded file
    public $name;  // Folder name or file name
    public $parentFolderId;  // Nullable, used for assigning to a folder
    public $newFolderFile;  // Optional file upload when creating a folder

    // Validation rules
    protected $rules = [
        'name' => 'required|string|max:255', // Ensure name is valid
        'file' => 'nullable|file|mimes:jpg,png,pdf,docx',  // Adjust file types as necessary
        'newFolderFile' => 'nullable|file|mimes:jpg,png,svg,pdf,docx,txt,mp4',
    ];

    // Handling form submission based on the type
    public function submit()
    {
        if ($this->type === 'file') {
            $this->handleFileUpload();
        } elseif ($this->type === 'folder') {
            $this->handleFolderCreation();
        }

        // Close the modal after processing
        $this->dispatch('closeModal');
        $this->dispatch('refreshPage');
    }

    protected function handleFileUpload()
    {
        if ($this->file) {
            // Default to root folder path
            $folderPath = 'offices'; // Root path by default
            $fileName = $this->file->getClientOriginalName();
    
            // If parentFolderId is null or empty, treat it as root folder
            if (empty($this->parentFolderId)) {
                $this->parentFolderId = null; // Explicitly set to null for clarity
                
                // Check if a file with the same name exists in the root folder (offices)
                $existingFileInRootFolder = Office::where('parent_id', null)
                                                  ->where('name', $fileName)
                                                  ->where('type', 'file')
                                                  ->first();
    
                if ($existingFileInRootFolder) {
                    // Dispatch event to show a banner instead of storing the file
                    $this->dispatch('showBanner', 'Errore durante l\'upload', 'Un file con questo nome è già presente nella cartella principale', 'error');
                    return; // Exit the method without storing the file
                }
            } else {
                // Check if a parent folder is selected and get its name for the storage path
                $folder = Office::find($this->parentFolderId);
                $folderPath = 'offices/' . $folder->name; // Define the path based on the folder name
    
                // Check if a file with the same name exists in the selected parent folder
                $existingFileInParentFolder = Office::where('parent_id', $this->parentFolderId)
                                                    ->where('name', $fileName)
                                                    ->where('type', 'file')
                                                    ->first();
    
                if ($existingFileInParentFolder) {
                    // Dispatch event to show a banner instead of storing the file
                    $this->dispatch('showBanner', 'Errore durante l\'upload', 'Un file con questo nome è già presente nella cartella selezionata', 'error');
                    return; // Exit the method without storing the file
                }
            }
    
            // Store the file with its original name in the defined folder path
            try {
                $path = $this->file->storeAs($folderPath, $fileName, 'public');
    
                // Save the file entry to the database
                Office::create([
                    'name' => $fileName,
                    'type' => 'file',
                    'path' => $path,  // Save the relative file path in the database
                    'parent_id' => $this->parentFolderId // Assign the file to the correct folder (null for root)
                ]);
            } catch (\Exception $e) {
                Log::error('File upload failed: ' . $e->getMessage());
                return redirect()->route('office.index')->with('error', ['title' => 'Errore', 'subtitle' => 'qualcosa è andato storto']);
            }
        }
    }
    
    protected function handleFolderCreation()
    {
        $this->validate();  // Ensure folder name is valid

        // Check if a folder with the same name exists in the same parent folder
        $existingFolder = Office::where('parent_id', $this->parentFolderId)
                                ->where('name', $this->name)
                                ->where('type', 'folder')
                                ->first();

        if ($existingFolder) {
            return redirect()->route('office.index')->with('error', ['title' => 'Errore', 'subtitle' => 'Una cartella con questo nome è già presente qui.']);
        }

        // Build the full path for the folder based on its parent
        $folderPath = $this->buildFolderPath($this->parentFolderId) . $this->name;

        try {
            if ($this->parentFolderId === '') {
                $this->parentFolderId = null;
            }

            // Create the folder entry in the database
            $folder = Office::create([
                'name' => $this->name,
                'type' => 'folder',
                'parent_id' => $this->parentFolderId,
                'path' => $folderPath,
            ]);
        } catch (\Exception $e) {
            Log::error('Folder creation in DB failed: ' . $e->getMessage());
            return redirect()->route('office.index')->with('error', ['title' => 'Error', 'subtitle' => 'An error occurred while creating the folder entry in the database.']);
        }

        // Create the folder in the storage using Laravel's Storage facade
        if (!Storage::exists($folderPath)) {
            try {
                Storage::makeDirectory($folderPath); // Create the directory with the appropriate permissions
            } catch (\Exception $e) {
                Log::error('Folder creation in storage failed: ' . $e->getMessage());
                return redirect()->route('office.index')->with('error', ['title' => 'Error', 'subtitle' => 'An error occurred while creating the folder in storage.']);
            }
        }

        // Handle file upload if present
        if ($this->newFolderFile) {
            // Check if a file with the same name exists in the new folder
            $existingFile = Office::where('parent_id', $folder->id)
                                ->where('name', $this->newFolderFile->getClientOriginalName())
                                ->where('type', 'file')
                                ->first();

            if ($existingFile) {
                return redirect()->route('office.index')->with('error', ['title' => 'Error', 'subtitle' => 'A file with this name already exists in the new folder.']);
            }

            try {
                // Store the file with the original file name
                $path = $this->newFolderFile->storeAs($folderPath, $this->newFolderFile->getClientOriginalName(), 'public');
            } catch (\Exception $e) {
                Log::error('File storage failed: ' . $e->getMessage());
                return redirect()->route('office.index')->with('error', ['title' => 'Error', 'subtitle' => 'An error occurred during the file storage process.']);
            }

            try {
                // Save the file in the database
                Office::create([
                    'name' => $this->newFolderFile->getClientOriginalName(),
                    'type' => 'file',
                    'path' => $path,
                    'parent_id' => $folder->id,
                ]);
            } catch (\Exception $e) {
                Log::error('File creation in DB failed: ' . $e->getMessage());
                return redirect()->route('office.index')->with('error', ['title' => 'Error', 'subtitle' => 'An error occurred while creating the file entry in the database.']);
            }
        }
    }

    protected function buildFolderPath($parentFolderId)
    {
        // If no parent, return base path
        if (!$parentFolderId) {
            return 'offices/';
        }

        // Retrieve the parent folder from the database
        $parentFolder = Office::find($parentFolderId);

        // Recursively build the path to include all parent folders
        return $this->buildFolderPath($parentFolder->parent_id) . $parentFolder->name . '/';
}



public function getAllFolders($parentId = null, $prefix = '')
{
    // Fetch folders based on the parent_id (root if null)
    $folders = Office::where('parent_id', $parentId)
                     ->where('type', 'folder')
                     ->get();

    $allFolders = [];
    
    // Iterate through each folder and fetch its children recursively
    foreach ($folders as $folder) {
        // Add the folder to the list with a prefix for indentation
        $allFolders[] = (object) [
            'id' => $folder->id,
            'name' => $prefix . $folder->name,
        ];

        // Recursively get all child folders with additional indentation
        $subFolders = $this->getAllFolders($folder->id, $prefix . $folder->name . ' > ');
        $allFolders = array_merge($allFolders, $subFolders);
    }
    
    return $allFolders;
}

    public function render()
    {
        // Fetch all folders and subfolders recursively for the select dropdown
        $folders = $this->getAllFolders();
        return view('livewire.office-upload-modal', [
            'folders' => $folders
        ]);
    }

}
