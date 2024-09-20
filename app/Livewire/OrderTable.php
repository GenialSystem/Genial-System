<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class OrderTable extends Component
{
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

    public $orders;
    public $loadMore = true;

    protected $listeners = ['loadMoreOrders', 'refreshComponent' => '$refresh'];
    
    public function mount()
    {
        $this->orders = Order::take(20)->get();
    }

    public function loadMoreOrders()
    {
        // dd('asd');
        $lastOrderId = $this->orders->last()->id ?? 0;

        $newOrders = Order::where('id', '>', $lastOrderId)
            ->take(20)
            ->get();

        if ($newOrders->count() < 20) {
            $this->loadMore = false; // No more records to load
        }

        $this->orders = $this->orders->merge($newOrders);
    }
    
    public function updateState($id, $newState)
    {
        $record = $this->orders->find($id);
        $record->state = $newState;
        $record->save();
    }

    public function render()
    {
        return view('livewire.order-table', [
            'rows' => $this->orders,
            'states' => $this->states,
        ]);
    }

}