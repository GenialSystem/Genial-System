<?php

namespace App\Livewire;

use App\Models\CustomerInfo;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $dateFilter = '';

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    // Event listener to update date filter
    protected $listeners = ['dateFilterUpdated' => 'handleDateFilterUpdated', 'refreshComponent' => '$refresh'];

    public function handleDateFilterUpdated($dateFilter)
    {
        $this->dateFilter = $dateFilter;
        $this->resetPage();
    }

    public function render()
    {
        $query = CustomerInfo::query();

        // Apply search filters
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('user', function ($userQuery) {
                        // Group name and email inside an orWhere closure
                        $userQuery->where(function ($userSubQuery) {
                            $userSubQuery->where('name', 'like', "%{$this->searchTerm}%")
                                ->orWhere('email', 'like', "%{$this->searchTerm}%")
                                ->orWhere('city', 'like', "%{$this->searchTerm}%")
                                ->orWhere('cellphone', 'like', "%{$this->searchTerm}%");
                        });
                    })
                    ->orWhere('admin_name', 'like', "%{$this->searchTerm}%")
                    ->orWhere('assigned_cars_count', 'like', "%{$this->searchTerm}%")
                    ->orWhere('queued_cars_count', 'like', "%{$this->searchTerm}%")
                    ->orWhere('finished_cars_count', 'like', "%{$this->searchTerm}%");
            });
        }

        // Apply date filter
        if (!empty($this->dateFilter)) {
            $query->whereDate('created_at', $this->dateFilter);
        }

        // Paginate results
        $rows = $query->paginate(12);

        return view('livewire.customer-table', [
            'rows' => $rows,
        ]);
    }

    public function paginationView()
    {
        return 'custom-pagination';
    }
}
