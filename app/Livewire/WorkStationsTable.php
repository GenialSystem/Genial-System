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
                $q->where('id', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('customer', function ($customerQuery) {
                        $customerQuery->where('name', 'like', "%{$this->searchTerm}%")
                            ->orWhere('admin_name', 'like', "%{$this->searchTerm}%")
                        ;
                    })
                    ;
            });
        }

        $customers = CustomerInfo::all();
        $mechanics = MechanicInfo::all();
       
        return view('livewire.work-stations-table', [
            'rows' => $query->paginate(12), 'customers' => $customers, 'mechanics' => $mechanics
        ]);
    }
    
}
