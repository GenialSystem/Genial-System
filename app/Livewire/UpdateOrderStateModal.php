<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use LivewireUI\Modal\ModalComponent;

class UpdateOrderStateModal extends ModalComponent
{
    public $selectedRows = [];
    
    public $states = [
        'Riparata' => 'bg-[#EFF7E9]',
        'Nuova' => 'bg-[#FFF9EC]',
        'In lavorazione' => 'bg-[#E9EFF5]',
        'Annullata' => 'bg-[#FEF0F5]',
    ];

    public $statesText = [
        'Riparata' => 'text-[#7FBC4B]',
        'Nuova' => 'text-[#FCC752]',
        'In lavorazione' => 'text-[#5E66CC]',
        'Annullata' => 'text-[#DC0851]',
    ];
    public $newState = '';
    
    public function applyStateToSelectedRows()
    {
        Log::info('IN');
        if (!empty($this->selectedRows) && !empty($this->newState)) {
            Order::whereIn('id', $this->selectedRows)
                ->update(['state' => $this->newState]);

            $this->dispatch('updateSelectionBanner');
            $this->dispatch('closeModal');
        }
    }

    public function render()
    {
        return view('livewire.update-order-state-modal');
    }
}
