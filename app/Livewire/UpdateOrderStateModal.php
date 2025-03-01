<?php

namespace App\Livewire;

use App\Models\Invoice;
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

                if ($this->newState === 'Riparata' && $order->customer && $order->finish_date == null) {
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
            $mechanic->increment('repaired_count');
            //crea fatttura per tenico $mechanic->user->id
            Invoice::create([
                'iva' => 20,
                'order_id' => $order->id,
                'is_closed' => 0, //ora che la fattura viene generata lascio lo stato default aperto. Lo stato aperto è zero
                'price' =>  (($order->earn_mechanic_percentage / count($order->mechanics)) / 100) * floatval(str_replace(['.', ','], ['', '.'], $order->price)),
                'user_id' => $mechanic->user->id
               ]);
        }

        Invoice::create([
            'iva' => 20,
            'order_id' => $order->id,
            'is_closed' => 0, //ora che la fattura viene generata lascio lo stato default aperto. Lo stato aperto è zero
            'price' =>  floatval(str_replace(['.', ','], ['', '.'], $order->price)),
            'user_id' => $order->customer->user->id
           ]);

        $order->finish_date = now();
        $order->update();
    }

    public function render()
    {
        return view('livewire.update-order-state-modal');
    }
}
