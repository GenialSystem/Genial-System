<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerOrdersTable extends Component
{
    use WithPagination;

    public $orders; // Orders passed in from the parent component
    public $searchTerm = '';
    public $selectedState = ''; // New property for selected state filter

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

    public function mount($orders){
        $this->orders = $orders;
    }

    public function updateState($orderId, $newState)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->state = $newState;
            $order->save();
        }
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedSelectedState()
    {
        $this->resetPage(); // Reset pagination when state filter changes
    }

    public function render()
    {
        $query = Order::query()
            ->whereIn('id', $this->orders->pluck('id')) // Filter orders passed via mount
            ->when(!empty($this->searchTerm), function($q) {
                $q->where(function ($q) {
                    $q->where('id', 'like', "%{$this->searchTerm}%")
                        ->orWhere('state', 'like', "%{$this->searchTerm}%")
                        ->orWhere('plate', 'like', "%{$this->searchTerm}%")
                        ->orWhere('price', 'like', "%{$this->searchTerm}%")
                        ->orWhereHas('customer', function ($userQuery) {
                            $userQuery->where('id', 'like', "%{$this->searchTerm}%")
                                ->orWhereHas('user', function ($infoQuery) {
                                    $infoQuery->where('name', 'like', "%{$this->searchTerm}%")
                                              ->orWhere('surname', 'like', "%{$this->searchTerm}%");
                                });
                        })
                        ->orWhereHas('mechanics', function ($mechanicQuery) {
                            $mechanicQuery->where('name', 'like', "%{$this->searchTerm}%");
                        });
                });
            })
            ->when(!empty($this->selectedState), function($q) {
                // Apply state filter when selected
                $q->where('state', $this->selectedState);
            });

        // Paginate the results
        return view('livewire.customer-orders-table', [
            'rows' => $query->paginate(12),
        ]);
    }

    public function paginationView()
    {
        return 'custom-pagination';
    }
}
