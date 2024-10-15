<?php

namespace App\Livewire;

use App\Models\CustomerInfo;
use App\Models\MechanicInfo;
use App\Models\Workstation;
use LivewireUI\Modal\ModalComponent;

class WorkstationModal extends ModalComponent
{
    public Workstation $workstation;
    public $selectedCustomer;
    public $selectedMechanics = [];
    public $mechanics;

    public function mount($workstation = null)
    {
        $this->mechanics = MechanicInfo::all();

        if ($workstation) {
            $this->workstation = $workstation;
            $this->selectedCustomer = $this->workstation->customer_id;
            $this->selectedMechanics = $this->workstation->mechanics->pluck('id')->toArray();
        }
    }

    public function addMechanic($mechanicId)
    {
        if (!in_array($mechanicId, $this->selectedMechanics)) {
            $this->selectedMechanics[] = $mechanicId;
        }
    }

    public function removeMechanic($mechanicId)
    {
        $this->selectedMechanics = array_filter($this->selectedMechanics, function($id) use ($mechanicId) {
            return $id != $mechanicId;
        });
    }

    public function render()
    {
        $customers = CustomerInfo::all();
        return view('livewire.workstation-modal', [
            'customers' => $customers,
            'mechanics' => $this->mechanics,
        ]);
    }
}
