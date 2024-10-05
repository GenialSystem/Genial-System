<?php

namespace App\Livewire;

use Exception;
use LivewireUI\Modal\ModalComponent;

class DeleteButtonModal extends ModalComponent
{
    public $modelIds;  // Accept an array of IDs
    public $modelClass;
    public $customRedirect;

    public function delete()
    {
        try {
            $query = app($this->modelClass)::query();

            if (is_array($this->modelIds)) {
                // If it's an array, use whereIn
                $models = $query->whereIn('id', $this->modelIds)->get();
            } else {
                // If it's a single integer, use where
                $models = $query->where('id', $this->modelIds)->get();
            }
    
            if ($models->count()) {
                // Loop through each model and delete
                foreach ($models as $model) {
                    $model->delete();
                }
    
                session()->flash('success', [
                    'title' => 'Modelli eliminati con successo.',
                    'subtitle' => 'Gli elementi non saranno più visibili in questa pagina.',
                ]);
    
                // Handle the redirect logic
                if ($this->customRedirect) {
                    return redirect()->route($this->customRedirect);
                }
                $this->dispatch('closeModal');
                $this->dispatch('updateSelectionBanner');
                $this->dispatch('refreshComponent');
            } else {
                session()->flash('error', [
                    'title' => 'Qualcosa è andato storto.',
                    'subtitle' => 'Nessun elemento trovato.',
                ]);
            }
        } catch (Exception $e) {
          return back()->with('error', ['title' => 'Qualcosa è andato storto', 'subtitle' => $e->getMessage()]);
        }
        // Check if modelIds is an array or a single integer
       
    }


    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function render()
    {
        return view('livewire.delete-button-modal');
    }
}
