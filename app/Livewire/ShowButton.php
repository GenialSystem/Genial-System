<?php
namespace App\Livewire;

use Livewire\Component;

class ShowButton extends Component
{
    public $modelId;
    public $modelClass;
    public $customRoute = null; // Add a nullable parameter for custom route

    public function mount($modelId, $modelClass, $customRoute = null)
    {
        $this->modelId = $modelId;
        $this->modelClass = $modelClass;
        $this->customRoute = $customRoute; // Initialize the custom route
    }

    public function show()
    {
        // Dynamically resolve the model class
        $model = app($this->modelClass)::find($this->modelId);

        // Check if the model exists
        if ($model) {
            if ($this->customRoute) {
                // Redirect to the custom route if provided
                return redirect()->route('showDoneOrder', $model);
            }

            // Generate route dynamically based on the model name
            $modelName = strtolower(class_basename($this->modelClass));
            switch ($modelName) {
                case 'customerinfo':
                    $modelName = 'customer';
                    break;
                case 'mechanicinfo':
                    $modelName = 'mechanic';
                    break;

                default:
                    break;
            }
            return redirect()->route($modelName . 's.show', $model);
        }

        session()->flash('error', 'Model not found');
        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.show-button');
    }
}
