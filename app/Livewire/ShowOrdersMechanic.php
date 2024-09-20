<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ShowOrdersMechanic extends Component
{
    public $orders;
    public $searchTerm = '';
    public $originalOrders;

    public function mount(Collection $orders)
    {
        $this->originalOrders = $orders;
        $this->orders = $orders;
    }

    public function render()
    {
        return view('livewire.show-orders-mechanic', [
            'orders' => $this->orders,
        ]);
    }

    public function updatedSearchTerm()
    {
        $searchTerm = strtolower(trim($this->searchTerm));

        if (empty($searchTerm)) {
            $this->orders = $this->originalOrders;
        } else {
            $this->orders = $this->originalOrders->filter(function ($order) use ($searchTerm) {
                $customerName = strtolower(trim($order->customer->name));
                $plate = strtolower(trim($order->plate));

                return strpos($customerName, $searchTerm) !== false || strpos($plate, $searchTerm) !== false;
            });
        }
    }

}
