<?php

namespace App\Livewire;

use Livewire\Component;

class DeleteButton extends Component
{
    public $modelId;
    public $modelClass;
    public $modelName;

    public $customRedirect;
    public $showModal = false; // To control modal visibility

    public function mount($modelId, $modelClass, $modelName, $customRedirect = null)
    {
        $this->modelId = $modelId;
        $this->modelClass = $modelClass;
        $this->modelName = $modelName;
        $this->customRedirect = $customRedirect;
    }

    // Method to trigger the delete confirmation modal
    public function confirmDelete()
    { 

                $this->showModal = true;
    }

    // Method to delete the model
    public function delete()
    {
        $model = app($this->modelClass)::find($this->modelId);

        if ($model) {
            $model->delete();

            session()->flash('success', [
                'title' => 'Utente eliminato con successo.',
                'subtitle' => 'Non sarà più visibile in questa pagina.',
            ]);
            $this->showModal = false;

            if ($this->customRedirect) {
                return redirect()->route($this->customRedirect);
            }
            // Optional: Redirect or emit an event after deletion
            return redirect()->route($this->modelName . '.index');
        }

        session()->flash('error', 'Model not found.');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.delete-button');
    }
}
