<?php

namespace App\Livewire;

use App\Models\Estimate;
use App\Models\MechanicInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class EstimatesTable extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $selectedRows = [];
    public $selectAll = false;
    public $selectedState = ''; // For state filter
    public $selectedType = ''; // For type filter
    public $selectedMechanic = ''; // For mechanic filter

    public $states = [
        'Confermato' => 'bg-[#EFF7E9]',
        'Nuovo' => 'bg-[#FFF9EC]',
        'Poco interessati' => 'bg-[#FCEEEE]',
        'Rifiutato' => 'bg-[#FEF0F5]',
        'Archiviato' => 'bg-[#EBF5F3]',
    ];

    public $statesText = [
        'Confermato' => 'text-[#7FBC4B]',
        'Nuovo' => 'text-[#FCC752]',
        'Poco interessati' => 'text-[#E57A7A]',
        'Rifiutato' => 'text-[#DC0851]',
        'Archiviato' => 'text-[#68C9BB]',
    ];
    public $newState = '';

    public $selectedEstimate;
    public $typeColor = [
        'Preventivo combinato' => 'bg-[#7AA3E5]',
        'Preventivo leva bolli' => 'bg-[#A892D1]',
        'Carrozzeria' => 'bg-[#E68B69]',
    ];

    protected $listeners = ['selectionDeleted' => 'clearSelectedRows', 'openCustomModal' => 'showModal', 'openCustomModal' => 'showCustomModal', 'closeEditModal' => 'closeEditModal'];

    protected $queryString = ['searchTerm', 'selectedState'];
    public $isCustomer;
    
    public function applyStateToSelectedRows()
    {
        $this->selectAll = false;
        if (!empty($this->selectedRows) && !empty($this->newState)) {
            Estimate::whereIn('id', $this->selectedRows)
                ->update(['state' => $this->newState]);

            $this->selectedRows = [];
            $this->closeModal();
            $this->dispatch('rowsSelected', $this->selectedRows);
        }
    }

    public function updatedSearchTerm()
    {
        $this->clearSelectedRows();
        $this->resetPage();
    }

    public function updatedSelectedState()
    {
        $this->clearSelectedRows();
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRows = $this->getCurrentPageEstimatesIds();
        } else {
            $this->selectedRows = [];
        }

        $this->dispatch('rowsSelected', $this->selectedRows);
    }


    public function toggleRow($rowId)
    {
        $rowId = (string) $rowId;

        if (in_array($rowId, $this->selectedRows)) {
            $this->selectedRows = array_diff($this->selectedRows, [$rowId]);
        } else {
            $this->selectedRows[] = $rowId;
        }

        $this->selectedRows = array_values($this->selectedRows);
        $this->dispatch('rowsSelected', $this->selectedRows);
    }

    public function updateState($estimateId, $newState)
    {
        Log::info('change' . $estimateId);
        $estimate = Estimate::find($estimateId);
        if ($estimate) {
            $estimate->state = $newState;
            $estimate->update();
        }
    }

    protected function getCurrentPageEstimatesIds()
    {
        // Use the same query conditions as in render() but only get IDs
        return Estimate::query()
            ->when(!empty($this->selectedState), function ($q) {
                $q->where('state', $this->selectedState);
            })
            ->when(!empty($this->searchTerm), function ($q) {
                $q->where(function ($q) {
                    $q->where('id', 'like', "%{$this->searchTerm}%")
                        ->orWhere('state', 'like', "%{$this->searchTerm}%")
                        ->orWhere('type', 'like', "%{$this->searchTerm}%")
                        ->orWhereHas('customer', function ($userQuery) {
                            $userQuery->where('name', 'like', "%{$this->searchTerm}%")
                                ->orWhereHas('customerInfo', function ($infoQuery) {
                                    $infoQuery->where('city', 'like', "%{$this->searchTerm}%")
                                        ->orWhere('admin_name', 'like', "%{$this->searchTerm}%");
                                });
                        })
                        ->orWhere('price', 'like', "%{$this->searchTerm}%");
                });
            })
            ->paginate(12) // Ensure pagination here
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }


    public function clearSelectedRows()
    {
        $this->selectedRows = [];
        $this->selectAll = false;
        $this->dispatch('rowsSelected', $this->selectedRows);
    }

    public function render()
    {
        $query = Estimate::query();

        if ($this->isCustomer) {
            $query->where('customer_id', Auth::user()->customerInfo->id);
        }

        if (!empty($this->selectedState)) {
            $query->where('state', $this->selectedState);
        }

        if (!empty($this->selectedType)) {
            $query->where('type', $this->selectedType);
        }

        if (!empty($this->selectedMechanic)) {
            $query->where('mechanic_id', $this->selectedMechanic);
        }

        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->searchTerm}%")
                    ->orWhere('state', 'like', "%{$this->searchTerm}%")
                    ->orWhere('brand', 'like', "%{$this->searchTerm}%")
                    ->orWhere('plate', 'like', "%{$this->searchTerm}%")
                    ->orWhere('type', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('customer', function ($userQuery) {
                        $userQuery->where('id', 'like', "%{$this->searchTerm}%")
                            ->orWhere('admin_name', 'like', "%{$this->searchTerm}%")
                            ->orWhereHas('user', function ($infoQuery) {
                                $infoQuery->where('name', 'like', "%{$this->searchTerm}%")
                                    ->orWhere('surname', 'like', "%{$this->searchTerm}%");
                            });
                    })
                    ->orWhere('price', 'like', "%{$this->searchTerm}%");
            });
        }

        return view('livewire.estimates-table', [
            'rows' => $query->paginate(12), // Ensure pagination here
            'mechanics' => MechanicInfo::all(), // Fetch all mechanics for the dropdown
        ]);
    }

    public function paginationView()
    {
        return 'custom-pagination';
    }

}
