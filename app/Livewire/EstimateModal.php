<?php
namespace App\Livewire;

use App\Models\Estimate; // Ensure you have the Estimate model imported
use LivewireUI\Modal\ModalComponent;

class EstimateModal extends ModalComponent
{
    public $estimate;
    public $newType;
    public $types = [
        'Preventivo combinato' => 'Preventivo combinato',
        'Preventivo leva bolli' => 'Preventivo leva bolli',
        'Carrozzeria' => 'Carrozzeria',
    ];
    public $price;
    public $state;

    public function mount($estimate = null)
    {
        if ($estimate) {
            // Existing estimate
            $this->estimate = $estimate;
            $this->newType = $estimate->type;
            $this->price = $estimate->price;
            
        } else {
            // New estimate
            $this->estimate = null;
            $this->newType = 'Preventivo combinato'; // Default type
            $this->price = ''; // Default empty price
           
        }
    }

    public function updateEstimate()
    {
        if ($this->estimate) {
            // Update existing estimate
            $this->estimate->update([
                'type' => $this->newType,
                'price' => $this->price,
            ]);
        } else {
            // Create a new estimate
            Estimate::create([
                'type' => $this->newType,
                'price' => $this->price,
                'state' => 'Nuovo'
                // Add other necessary fields for creation
            ]);
        }

        session()->flash('success', [
            'title' => $this->estimate ? 'Preventivo aggiornato con successo.' : 'Nuovo preventivo creato con successo.',
            'subtitle' => 'Lorem ipsum',
        ]);

        return redirect()->route('estimates.index');
    }

    public function render()
    {
       
        return view('livewire.estimate-modal');
    }
}
