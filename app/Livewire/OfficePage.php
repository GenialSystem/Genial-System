<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Office;
use Illuminate\Support\Facades\Storage;

class OfficePage extends Component
{
    public $searchTerm = '';    // For filtering
    public $folderId = null;    // Tracks current folder ID
    protected $listeners = ['refreshPage' => '$refresh'];

    // Fetch files and folders, filtered by search term
    public function getFilteredItemsProperty()
    {
        return Office::where('parent_id', $this->folderId)
            ->where('name', 'like', '%' . $this->searchTerm . '%')
            ->get();
    }

    // Create a new folder
    public function createFolder()
    {
        Office::create([
            'name' => $this->newFolderName,
            'type' => 'folder',
            'parent_id' => $this->folderId,
        ]);

        $this->reset('newFolderName'); // Clear the input after creation
    }

    // Navigate into a folder (change folder context)
    public function enterFolder($folderId)
    {
        $this->folderId = $folderId;
    }

    // Navigate back to the parent folder
    public function goBack()
    {
        $parentFolder = Office::find($this->folderId)->parent_id;
        $this->folderId = $parentFolder;
    }

    public function downloadItem($itemId)
    {
        $item = Office::find($itemId);
    
        // Check if the item is a file
        if ($item->type === 'file') {
            // Download the file using the path stored in the database
            return response()->download(storage_path('app/' . $item->path));
        }
    
        // Check if the item is a folder
        if ($item->type === 'folder') {
            $folderPath = storage_path('app/' . $item->path);
    
            // Check if the folder exists
            if (!is_dir($folderPath)) {
                abort(404, 'The specified folder does not exist: ' . $folderPath);
            }
    
            // Check if the folder is empty
            if (count(scandir($folderPath)) <= 2) { // Only '.' and '..' will be present if empty
                return response()->json(['message' => 'The specified folder is empty.'], 200);
            }
    
            // Create a zip file for the folder
            $zipFileName = basename($folderPath) . '.zip';
            $zipFilePath = storage_path('app/' . $zipFileName);
    
            // Initialize ZipArchive
            $zip = new \ZipArchive();
            if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                // Recursively add folder contents to the zip
                $this->addFolderToZip($folderPath, $zip);
                $zip->close();
                
                // Check if the ZIP file was created successfully
                if (!file_exists($zipFilePath)) {
                    abort(500, 'Failed to create zip file: ' . $zipFilePath);
                }
    
                // Return the zip file for download
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            } else {
              
                abort(500, 'Could not create zip file.');
            }
        }
    }
    

    private function addFolderToZip($folderPath, \ZipArchive $zip, $zipPath = '')
    {
        // Get all files and folders in the directory
        $files = scandir($folderPath);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue; // Skip current and parent directory
            }
            
            $fullPath = $folderPath . '/' . $file; // Use '/' for cross-platform compatibility
            $zipEntryPath = $zipPath . $file; // The path for the entry in the zip
            
            if (is_dir($fullPath)) {
                $zip->addEmptyDir($zipEntryPath);
                $this->addFolderToZip($fullPath, $zip, $zipEntryPath . '/'); // Recursive call
            } else {
                $zip->addFile($fullPath, $zipEntryPath);
            }
        }
    }

    public function deleteItem($itemId)
    {
        $item = Office::find($itemId);

        // If item is a folder, delete all contents
        if ($item->type === 'folder') {
            // Fetch all files/folders inside the folder
            $contents = Office::where('parent_id', $itemId)->get();

            foreach ($contents as $content) {
                // Delete from storage
                Storage::delete($content->path); // Assuming path is stored as a relative path
                
                // Delete from the database
                $content->delete();
            }

            // After deleting all contents, delete the folder from storage
            Storage::deleteDirectory('offices/' . $item->name); // Delete the folder from storage
        } else {
            // If it's a file, delete from storage
            Storage::delete($item->path); // Delete the file from storage
        }

        // Delete the item itself
        $item->delete();

        // Optionally refresh the page or reset the state
        $this->dispatch('refreshPage');
    }
   

    public function render()
    {
        return view('livewire.office-page', [
            'items' => $this->filteredItems, // Return filtered files/folders
        ]);
    }
}
