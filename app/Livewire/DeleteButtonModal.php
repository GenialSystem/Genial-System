<?php

namespace App\Livewire;

use LivewireUI\Modal\ModalComponent;

class DeleteButtonModal extends ModalComponent
{

    public $modelId;
    public $modelClass;
    public $modelName;

    public $customRedirect;

    public function delete()
    {
        $model = app($this->modelClass)::find($this->modelId);

        if ($model) {
            $model->delete();

            session()->flash('success', [
                'title' => 'Utente eliminato con successo.',
                'subtitle' => 'Non sarà più visibile in questa pagina.',
            ]);
           

            if ($this->customRedirect) {
                return redirect()->route($this->customRedirect);
            }
            // Optional: Redirect or emit an event after deletion
            return redirect()->route($this->modelName . '.index');
        }

        session()->flash('error', [
            'title' => 'Qualcosa è andato storto.',
            'subtitle' => 'Item not found',
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
