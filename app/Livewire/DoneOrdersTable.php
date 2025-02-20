<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class DoneOrdersTable extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $bg_colors = [
        'red' => 'bg-[#FCE5E8]',
        'green' => 'bg-[#E7FAF4]',
        'black' => 'bg-[#D6D6D6]',
        'white' => 'bg-[#F5F5F5]',
        'yellow' => 'bg-[#FAF2DD]',
        'purple' => 'bg-[#DC76E0]'
    ];

    public $text_colors = [
        'red' => 'text-[#DC0814]',
        'green' => 'text-[#92D1BB]',
        'black' => 'text-[#464646]',
        'white' => 'text-[#9F9F9F]',
        'yellow' => 'text-[#E8C053]',
        'purple' => 'text-[#FFF2FF]'
    ];

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function render()
    {

        $query = Order::where('state', 'Riparata');


        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->searchTerm}%")
                    ->orWhere('state', 'like', "%{$this->searchTerm}%")
                    ->orWhere('payment', 'like', "%{$this->searchTerm}%")
                    ->orWhere('plate', 'like', "%{$this->searchTerm}%")
                    ->orWhere('price', 'like', "%{$this->searchTerm}%")
                    ->orWhere('brand', 'like', "%{$this->searchTerm}%")
                    ->orWhere('color', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('customer', function ($userQuery) {
                        $userQuery->where('admin_name', 'like', "%{$this->searchTerm}%")
                            ->orWhereHas('user', function ($infoQuery) {
                                $infoQuery->where('city', 'like', "%{$this->searchTerm}%")
                                ->orWhere('name', 'like', "%{$this->searchTerm}%")
                                ->orWhere('surname', 'like', "%{$this->searchTerm}%");
                            });
                    })
                    ->orWhereHas('mechanics', function ($mechanicQuery) {
                        $mechanicQuery->whereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'like', "%{$this->searchTerm}%");
                        });
                    });
                    
            });
        }

        $query->orderBy('id', 'desc');

        return view('livewire.done-orders-table', [
            'rows' => $query->paginate(12),
        ]);
    }

    public function paginationView()
    {
        return 'custom-pagination';
    }
}
