<?php

namespace App\Livewire;

use App\Models\MechanicInfo;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MainOrderTable extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $selectedRows = [];
    
    public $dateFilter = '';

    public $selectAll = false;

    public $dropdownOpen = [];

    public $showModal = false;

    public $selectedMechanic = '';

    public ?bool $isCustomer = null;

    protected $listeners = ['selectionDeleted' => 'clearSelectedRows', 'dateFilterUpdated' => 'handleDateFilterUpdated'];

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

    public function handleDateFilterUpdated($dateFilter)
    {
        $this->dateFilter = $dateFilter;
        $this->resetPage();
    }

    public function updateState($orderId, $newState)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->state = $newState;
            $order->save();
        }
    }

    public function clearSelectedRows()
    {
        $this->selectedRows = [];
        $this->selectAll = false;
    }


    public function updatedSearchTerm()
    {
        $this->resetPage();
        $this->clearSelectedRows();
        $this->dispatch('rowsSelected', $this->selectedRows);

    }
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Only select rows visible on the current page
            $this->selectedRows = $this->getCurrentPageOrderIds();
        } else {
            $this->selectAll = false;
            $this->selectedRows = [];
        }

        // Dispatch event to update the selection
        $this->dispatch('rowsSelected', $this->selectedRows);
    }



    protected function getCurrentPageOrderIds()
    {
        // Use the paginator to get the IDs of the orders on the current page
        return Order::query()
            ->when(!empty($this->searchTerm), function ($q) {
                $q->where(function ($q) {
                    $q->where('id', 'like', "%{$this->searchTerm}%")
                        ->orWhere('state', 'like', "%{$this->searchTerm}%")
                        ->orWhere('plate', 'like', "%{$this->searchTerm}%")
                        ->orWhere('price', 'like', "%{$this->searchTerm}%")
                        ->orWhereHas('customer', function ($userQuery) {
                            $userQuery->where('name', 'like', "%{$this->searchTerm}%")
                                ->orWhereHas('customerInfo', function ($infoQuery) {
                                    $infoQuery->where('city', 'like', "%{$this->searchTerm}%")
                                        ->orWhere('admin_name', 'like', "%{$this->searchTerm}%");
                                });
                        })
                        ->orWhereHas('mechanics', function ($mechanicQuery) {
                            $mechanicQuery->where('name', 'like', "%{$this->searchTerm}%");
                        });
                });
            })
            ->paginate(12)
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
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

        // Dispatch event to update the selection
        $this->dispatch('rowsSelected', $this->selectedRows);
    }

    public function render()
    {
        $query = Order::query();

        // If the boolean flag is true, fetch orders assigned to the authenticated user (customer)
        if ($this->isCustomer) {
            $query->where('customer_id', Auth::user()->customerInfo->id);
        }

        // Apply search term filter
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->searchTerm}%")
                    ->orWhere('state', 'like', "%{$this->searchTerm}%")
                    ->orWhere('plate', 'like', "%{$this->searchTerm}%")
                    ->orWhere('price', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('customer', function ($customerQuery) {
                        $customerQuery->where('admin_name', 'like', "%{$this->searchTerm}%")
                            ->orWhereHas('user', function ($userQuery) {
                                $userQuery->where('name', 'like', "%{$this->searchTerm}%")
                                        ->orWhere('surname', 'like', "%{$this->searchTerm}%");
                            });
                    })
                    ->orWhereHas('mechanics', function ($mechanicQuery) {
                        $mechanicQuery->where('name', 'like', "%{$this->searchTerm}%");
                    });
            });
        }

        // Apply date filter
        if (!empty($this->dateFilter)) {
            $query->whereDate('created_at', '=', $this->dateFilter);
        }

        // Apply mechanic filter
        if (!empty($this->selectedMechanic)) {
            $query->whereHas('mechanics', function ($mechanicQuery) {
                $mechanicQuery->where('users.id', '=', $this->selectedMechanic);  // Correct column reference for mechanic ID
            });
        }

        return view('livewire.main-order-table', [
            'rows' => $query->paginate(12),  // Paginate results
            'mechanics' => MechanicInfo::all(),  // Provide mechanics for the dropdown filter
        ]);
    }

    public function paginationView()
    {
        return 'custom-pagination';
    }
    
}
