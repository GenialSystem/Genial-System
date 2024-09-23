<?php

namespace App\Livewire;

use LivewireUI\Modal\ModalComponent;

class DeleteButton extends ModalComponent
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

    public function render()
    {
        return view('livewire.delete-button');
    }
}
