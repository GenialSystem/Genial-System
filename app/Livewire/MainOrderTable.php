<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class MainOrderTable extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $selectedRows = [];
    
    public $dateFilter = '';

    public $selectAll = false;

    public $dropdownOpen = [];

    public $showModal = false;

    protected $listeners = ['selectionDeleted' => 'clearSelectedRows', 'openModal' => 'showModal', 'dateFilterUpdated' => 'handleDateFilterUpdated'];

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

    public function toggleDropdown($rowId)
    {
        if (isset($this->dropdownOpen[$rowId])) {
            $this->dropdownOpen[$rowId] = !$this->dropdownOpen[$rowId];
        } else {
            $this->dropdownOpen[$rowId] = true;
        }
    }

    public function updateState($orderId, $newState)
    {

        $order = Order::find($orderId);
        if ($order) {
            $order->state = $newState;
            $order->save();
        }

    }

    public function applyStateToSelectedRows()
    {
        $this->selectAll = false;
        if (!empty($this->selectedRows) && !empty($this->newState)) {
            Order::whereIn('id', $this->selectedRows)
                ->update(['state' => $this->newState]);

            $this->selectedRows = [];
            $this->closeModal();
            $this->dispatch('rowsSelected', $this->selectedRows);
        }
    }


    public function showModal($selectedRows)
    {
        $this->selectedRows = $selectedRows;
        $this->showModal = true; // Set to true to open the modal
    }

    public function closeModal()
    {
        $this->showModal = false; // Set to false to close the modal
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
                        ->orWhere('color', 'like', "%{$this->searchTerm}%")
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

        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->searchTerm}%")
                    ->orWhere('state', 'like', "%{$this->searchTerm}%")
                    ->orWhere('color', 'like', "%{$this->searchTerm}%")
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
        }
        // Apply date filter
        if (!empty($this->dateFilter)) {
            $query->whereDate('created_at', '=', $this->dateFilter);
        }


        // Paginate the results for display
        return view('livewire.main-order-table', [
            'rows' => $query->paginate(12),
        ]);
    }

    
}
