<?php

namespace App\Livewire;

use LivewireUI\Modal\ModalComponent;

class DeleteButtonModal extends ModalComponent
{
    public $modelIds;  // Accept an array of IDs
    public $modelClass;
    public $customRedirect;

    public function delete()
    {
        // Fetch the models based on the array of IDs
        $models = app($this->modelClass)::whereIn('id', $this->modelIds)->get();

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
        }

        session()->flash('error', [
            'title' => 'Qualcosa è andato storto.',
            'subtitle' => 'Nessun elemento trovato.',
        ]);
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
