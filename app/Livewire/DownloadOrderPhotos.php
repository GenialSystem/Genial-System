<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use ZipArchive;


class DownloadOrderPhotos extends Component
{
    public $orderId;

    public function downloadPhotos()
    {
        // Check if order ID exists, if not, return an error message
        if (!$this->orderId || !Order::find($this->orderId)) {
            $this->dispatch('showBanner', 'Errore durante il download', 'Ordine non trovato.', 'error');
            return;
        }

        // Define paths to the 'normal' and 'disassembly' directories
        $normalPath = storage_path("app/public/orders/{$this->orderId}/normal");
        $disassemblyPath = storage_path("app/public/orders/{$this->orderId}/disassembly");

        // Check if either directory has files before proceeding
        if (!$this->directoryHasFiles($normalPath) && !$this->directoryHasFiles($disassemblyPath)) {
            $this->dispatch('showBanner', 'Errore durante il download', 'Nessuna foto presente in questa commessa.', 'error');

            return;
        }

        // Create a temporary zip file
        $zipFile = tempnam(sys_get_temp_dir(), 'order_photos_') . '.zip';

        // Initialize the ZipArchive
        $zip = new ZipArchive;
        if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
            // Add files from 'normal' folder
            $this->addFilesToZip($zip, $normalPath, 'normal');

            // Add files from 'disassembly' folder
            $this->addFilesToZip($zip, $disassemblyPath, 'disassembly');

            $zip->close();

            // Set the name of the zip file to "order-{id}.zip"
            $downloadFileName = "order-{$this->orderId}.zip";

            // Return the zipped file as a download with the specified name
            return response()->download($zipFile, $downloadFileName)->deleteFileAfterSend(true);
        } else {
            $this->dispatch('showBanner', 'Errore durante il download', 'Impossibile creare il file ZIP.', 'error');
        }
    }


    // Helper function to check if a directory contains files
    private function directoryHasFiles($directory)
    {
        return is_dir($directory) && count(scandir($directory)) > 2; // More than '.' and '..'
    }

    // Helper function to add files to zip archive
    private function addFilesToZip(ZipArchive $zip, $folderPath, $folderName)
    {
        if (is_dir($folderPath)) {
            $files = scandir($folderPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $folderPath . '/' . $file;
                    if (is_file($filePath)) {
                        $zip->addFile($filePath, "$folderName/" . basename($filePath));
                    }
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.download-order-photos');
    }
}
