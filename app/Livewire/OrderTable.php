<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Notifications\OrderFinished;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            // Admins can see all orders
            $this->orders = Order::orderBy('id', 'desc')->take(20)->get();
        } elseif ($user->hasRole('customer')) {
            $this->orders = Order::where('customer_id', $user->customerInfo->id)->orderBy('id', 'desc')->take(20)->get();
            // dd($this->orders);
        } elseif ($user->hasRole('mechanic')) {
            // Mechanics see only orders assigned to them
            $this->orders = $user->mechanicInfo->orders()->orderBy('id', 'desc')->take(20)->get();
        }
    }


    public function loadMoreOrders()
    {
        $user = Auth::user();
        $lastOrderId = $this->orders->last()->id ?? 0;

        if ($user->hasRole('admin')) {
            $newOrders = Order::where('id', '<', $lastOrderId)
                ->orderBy('id', 'desc')
                ->take(20)
                ->get();
        } elseif ($user->hasRole('customer')) {
            $newOrders = Order::where('customer_id', $user->customerInfo->id)
                ->where('id', '<', $lastOrderId)  // Specify table for `id`
                ->orderBy('id', 'desc')
                ->take(20)
                ->get();
        } elseif ($user->hasRole('mechanic')) {
            $newOrders = $user->mechanicInfo->orders()
                ->where('orders.id', '<', $lastOrderId)  // Specify table for `id`
                ->orderBy('orders.id', 'desc')
                ->take(20)
                ->get();
        }

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
        if ($newState == 'Riparata' && $record->customer) {
            $creator = Auth::user()->getFullName();
            $record->customer->user->notify(new OrderFinished($creator,  $record->id));
        }
    }

    public function render()
    {
        return view('livewire.order-table', [
            'rows' => $this->orders,
            'states' => $this->states,
        ]);
    }

}