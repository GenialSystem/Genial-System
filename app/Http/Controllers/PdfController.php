<?php

namespace App\Http\Controllers;
set_time_limit(120);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Spatie\Browsershot\Browsershot;

class PdfController extends Controller
{
    public function downloadPdfs($model, $ids)
    {
        try {
            // Convert comma-separated IDs to an array
            $idArray = explode(',', $ids);
    
            // Dynamically resolve the model class from the string
            $modelClass = 'App\\Models\\' . ucfirst($model);
    
            // Check if the model class exists
            if (!class_exists($modelClass)) {
                return response()->json(['error' => 'Invalid model specified'], 400);
            }
    
            // Define the path where PDFs will be saved (inside storage, not public)
            $pdfPath = storage_path("app/temp/");
            if (!File::exists($pdfPath)) {
                File::makeDirectory($pdfPath, 0755, true);
            }
            // Handle a single ID case (download the PDF directly)
            if (count($idArray) === 1) {
                $instance = $modelClass::findOrFail($idArray[0]); // Get the model instance
                $view = view("pdf.{$model}", [$model => $instance])->render(); // Render view
                Log::info($model);
                Log::info($instance);
                if ($model == 'invoice' || $model == 'estimate') {
                    $pdfFileName = "{$model}-" . str_replace('/', '_', $instance->number) . ".pdf";
                } else {
                    $pdfFileName = "{$model}-{$instance->id}.pdf";
                }
                
                $pdfFullPath = $pdfPath . $pdfFileName; // Full path
                
                // Generate the PDF
                Browsershot::html($view)
                    ->waitUntilNetworkIdle()
                    ->setOption('printBackground', true)
                    ->save($pdfFullPath);
    
                // Download the PDF and delete temp file after download
                return response()->download($pdfFullPath)
                    ->deleteFileAfterSend(true); // Deletes the single PDF after download
            }
    
            // Handle multiple IDs (generate a zip folder)
            $zip = new ZipArchive();
            $zipFileName = "{$model}_pdfs.zip"; // Create a zip file name
            $zipFilePath = $pdfPath . $zipFileName;
    
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                $pdfFiles = []; // Array to store generated PDF file paths
    
                foreach ($idArray as $id) {
                    $instance = $modelClass::findOrFail($id);
                    $view = view("pdf.{$model}", [$model => $instance])->render(); // Render view
                    if ($model == 'invoice' || $model == 'estimate') {
                        $pdfFileName = "{$model}-" . str_replace('/', '_', $instance->number) . ".pdf";
                    } else {
                        $pdfFileName = "{$model}-{$instance->id}.pdf";
                    }
                    $pdfFullPath = $pdfPath . $pdfFileName;
    
                    // Generate the PDF
                    Browsershot::html($view)
                    ->waitUntilNetworkIdle()
                    ->setOption('printBackground', true)
                    ->setOption('args', ['--no-sandbox'])
                    ->setOption('executablePath', '/usr/bin/chromium-browser')
                    ->save($pdfFullPath);
                
    
                    // Add the PDF to the zip archive
                    if (!$zip->addFile($pdfFullPath, $pdfFileName)) {
                        // Log any errors while adding files to the zip
                        Log::error("Failed to add file to zip: {$pdfFullPath}");
                    }
                    $pdfFiles[] = $pdfFullPath; // Store the path for deletion later
                }
    
                // Close the zip archive
                $zip->close();
    
                // Flush output buffers
                ob_end_clean();
    
                // Prepare the zip file for download
                return response()->download($zipFilePath)->deleteFileAfterSend(true); // Deletes the zip after download
            } else {
                return response()->json(['error' => 'Unable to create zip file'], 500);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', ['title' => 'Errore durante il download.', 'subtitle' => $e->getMessage()]);
        } finally {
            // Cleanup: delete PDF files after processing
            foreach ($pdfFiles ?? [] as $pdfFile) {
                if (File::exists($pdfFile)) {
                    File::delete($pdfFile);
                }
            }
        }
    }
    
    
}
