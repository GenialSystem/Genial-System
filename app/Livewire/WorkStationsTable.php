<?php

namespace App\Livewire;

use App\Models\CustomerInfo;
use App\Models\MechanicInfo;
use App\Models\Workstation;
use Livewire\Component;

class WorkStationsTable extends Component
{
    public $searchTerm = '';


    public function render()
    {
        $query = Workstation::query();
    
        // Filter by search term
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->searchTerm}%")->orWhere('city', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('customer', function ($customerQuery) {
                        $customerQuery->where('admin_name', 'like', "%{$this->searchTerm}%")
                        ->orWhereHas('user', function ($customerQuery) {
                            $customerQuery->where('name', 'like', "%{$this->searchTerm}%")
                            ->orWhere('surname', 'like', "%{$this->searchTerm}%");
                               
                        });
                    });
            });
        }
        $query->orderBy('id', 'desc');

        return view('livewire.work-stations-table', [
            'rows' => $query->paginate(12)
        ]);
    }
    
    public function paginationView()
    {
        return 'custom-pagination';
    }
}
