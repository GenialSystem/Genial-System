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
        set_time_limit(300);  // Aumenta il tempo di esecuzione per evitare timeout

        try {
            // Converti gli ID separati da virgola in un array
            $idArray = explode(',', $ids);
            
            // Risolvi dinamicamente il modello
            $modelClass = 'App\\Models\\' . ucfirst($model);
            
            // Verifica se il modello esiste
            if (!class_exists($modelClass)) {
                return response()->json(['error' => 'Invalid model specified'], 400);
            }
            
            // Definisci la cartella di salvataggio dei PDF
            $pdfPath = storage_path("app/temp/");
            if (!File::exists($pdfPath)) {
                File::makeDirectory($pdfPath, 0755, true);
            }

            // Crea un file ZIP per contenere i PDF
            $zip = new ZipArchive();
            $zipFileName = "{$model}_pdfs.zip";
            $zipFilePath = $pdfPath . $zipFileName;
            
            // Prova ad aprire il file ZIP
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                
                // Creazione di PDF in parallelo per ogni ID
                foreach ($idArray as $id) {
                    dispatch(function() use ($model, $id, $pdfPath) {
                        $this->generateAndSavePdf($model, $id, $pdfPath);
                    });
                }
                
                // Qui puoi sincronizzare o aspettare che tutti i job siano completati
                // Dopo aver generato i PDF, aggiungili al file ZIP
                foreach ($idArray as $id) {
                    $instance = $modelClass::findOrFail($id);
                    $pdfFileName = "{$model}-{$instance->id}.pdf";
                    $pdfFullPath = $pdfPath . $pdfFileName;
                    
                    if (File::exists($pdfFullPath)) {
                        $zip->addFile($pdfFullPath, $pdfFileName);
                    }
                }

                // Chiudi il file ZIP
                $zip->close();

                // Rimuovi i PDF temporanei dopo il download
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            }

            return response()->json(['error' => 'Unable to create zip file'], 500);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', ['title' => 'Errore durante il download.', 'subtitle' => $e->getMessage()]);
        }
    }

    private function generateAndSavePdf($model, $id, $pdfPath)
    {
        $modelClass = 'App\\Models\\' . ucfirst($model);
        $instance = $modelClass::findOrFail($id);

        $view = view("pdf.{$model}", [$model => $instance])->render();
        $pdfFileName = "{$model}-{$instance->id}.pdf";
        $pdfFullPath = $pdfPath . $pdfFileName;

        // Ottimizzazione Browsershot per maggiore velocità
        Browsershot::html($view)
            ->waitUntilNetworkIdle()
            ->setOption('printBackground', true)
            ->setOption('executablePath', '/usr/bin/chromium-browser') // Percorso a Chromium
            ->save($pdfFullPath);
    }
 
}