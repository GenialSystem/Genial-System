<?php

namespace App\Livewire;

use Livewire\Component;

class EditButton extends Component
{
    public $modelId;
    public $openModal;
    public $modelName;
    public $modelClass; // Dynamic model class

    public function mount($modelId, $modelName, $modelClass)
    {
        $this->modelId = $modelId;
        // $this->openModal = $openModal;
        $this->modelName = $modelName;
        $this->modelClass = $modelClass; // Assign the dynamic model class
    }

    public function edit()
    {
        // Dynamically resolve the model class
        $model = app($this->modelClass)::find($this->modelId);

        if ($this->openModal) {
            $this->dispatch('openCustomModal', $model);
            return;
        }

        // Check if the model exists
        if ($model) {
            return redirect()->route($this->modelName . 's.edit', [$this->modelName => $this->modelId]);
        }

        session()->flash('error', 'Model not found');
        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.edit-button');
    }
}
