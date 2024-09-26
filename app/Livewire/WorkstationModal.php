<?php

namespace App\Livewire;

use App\Models\CustomerInfo;
use App\Models\MechanicInfo;
use LivewireUI\Modal\ModalComponent;

class WorkstationModal extends ModalComponent
{
    public function render()
    {
        $customers = CustomerInfo::all();
        $mechanics = MechanicInfo::all();
        return view('livewire.workstation-modal', ['customers' => $customers, 'mechanics' => $mechanics]);
    }
}
