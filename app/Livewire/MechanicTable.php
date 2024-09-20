<?php

namespace App\Livewire;

use App\Models\CustomerInfo;
use App\Models\MechanicInfo;
use Livewire\Component;
use Livewire\WithPagination;

class MechanicTable extends Component
{
    use WithPagination;
    public $dateFilter = '';
    public $searchTerm = '';
    protected $listeners = ['dateFilterUpdated' => 'handleDateFilterUpdated'];

    public function handleDateFilterUpdated($dateFilter)
    {
        $this->dateFilter = $dateFilter;
        $this->resetPage();
        // Reset pagination when the date filter changes
    }
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }
    public function render()
    {
        $query = MechanicInfo::query();

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
                    ->orWhere('repaired_count', 'like', "%{$this->searchTerm}%")
                    ->orWhere('surname', 'like', "%{$this->searchTerm}%")
                    ->orWhere('working_count', 'like', "%{$this->searchTerm}%");
            });
        }
        if (!empty($this->dateFilter)) {
            $query->whereDate('created_at', $this->dateFilter);

        }

        $rows = $query->paginate(12);

        return view('livewire.mechanic-table', [
            'rows' => $rows,
        ]);
    }

}
