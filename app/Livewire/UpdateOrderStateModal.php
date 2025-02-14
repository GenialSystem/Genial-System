<?php

namespace App\Livewire;

use App\Models\Order;
use App\Notifications\OrderFinished;
use Illuminate\Support\Facades\Auth;
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
        if (!empty($this->selectedRows) && !empty($this->newState)) {
            // Retrieve the selected orders
            $orders = Order::whereIn('id', $this->selectedRows)->get();

            foreach ($orders as $order) {
                // Update the state of each order
                $order->state = $this->newState;
                $order->save();

                if ($this->newState === 'Riparata' && $order->customer) {
                    $this->handleOrderFinish($order);
                }
            }

            // Dispatch events after all updates
            $this->dispatch('updateSelectionBanner');
            $this->dispatch('closeModal');
        }
    }

    private function handleOrderFinish($order)
    {
        $creator = Auth::user()->getFullName();
        $order->customer->user->notify(new OrderFinished($creator, $order->id));
        $order->customer->increment('finished_cars_count');

        foreach ($order->mechanics as $mechanic) {
            $mechanic->mechanicInfo->increment('repaired_count');
        }
    }

    public function render()
    {
        return view('livewire.update-order-state-modal');
    }
}
