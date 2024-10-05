<?php
namespace App\Livewire;

use App\Models\CustomerInfo;
use App\Models\Estimate; // Ensure you have the Estimate model imported
use LivewireUI\Modal\ModalComponent;

class EstimateModal extends ModalComponent
{
    public Estimate $estimate;
    public $newType;
    public $types = [
        'Preventivo combinato' => 'Preventivo combinato',
        'Preventivo leva bolli' => 'Preventivo leva bolli',
        'Carrozzeria' => 'Carrozzeria',
    ];
    public $price;
    public $state;
    public $customers;
    public $selectedCustomer;
    public $estimateNumber;

    // public $number;

    public function mount($estimate = null)
    {
        $lastEstimate = Estimate::latest('id')->first();
       
        $nextEstimateNumber = ($lastEstimate ? $lastEstimate->id + 1 : 1) . '/' . now()->year;
        $this->estimateNumber = $nextEstimateNumber;
        $this->customers = CustomerInfo::all();
        if ($estimate) {
            
            $this->estimate = $estimate;
            $this->newType = $estimate->type;
            $this->price = $estimate->price;
            $this->selectedCustomer = $estimate->customer_id;
        } else {
            // New estimate
            $this->estimate = new Estimate();
            $this->newType = 'Preventivo combinato'; // Default type
            $this->price = ''; // Default empty price
            $this->selectedCustomer = null;
        }
    }

    public function updateEstimate()
    {
        try {
            //code...
            if ($this->estimate->id) {
                // Update existing estimate
                $this->estimate->update([
                    'customer_id' => $this->selectedCustomer,
                    'type' => $this->newType,
                    'price' => $this->price,
                ]);
            } else {
                // Create a new estimate
                Estimate::create([
                    'customer_id' => $this->selectedCustomer,
                    'type' => $this->newType,
                    'price' => $this->price,
                    'state' => 'Nuovo'
                    
                ]);
              
            }
    
            session()->flash('success', [
                'title' => $this->estimate->id ? 'Preventivo aggiornato con successo.' : 'Nuovo preventivo creato con successo.',
                'subtitle' => 'Il preventivo è stato aggiunto alla gestione preventivi',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', [
                'title' => 'Errore:',
                'subtitle' => $e->getMessage(),
            ]);
        }

        return redirect()->route('estimates.index');
    }

        /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '6xl';
    }

    public function render()
    {
        return view('livewire.estimate-modal');
    }
}
