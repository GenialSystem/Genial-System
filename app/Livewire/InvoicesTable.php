<?php

namespace App\Livewire;

use App\Models\Invoice;
use App\Models\User; // Import the User model
use Livewire\Component;
use Livewire\WithPagination;

class InvoicesTable extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $selectedRows = [];
    public $selectAll = false;
    public $selectedState = ''; // For state filter
    public $selectedIsClosed = ''; // For is_closed filter
    public $selectedCustomerOrMechanic = ''; // For customer or mechanic filter
    public $role;

    protected $listeners = ['selectionDeleted' => 'clearSelectedRows'];
    protected $queryString = ['searchTerm', 'selectedState', 'selectedIsClosed', 'selectedCustomerOrMechanic'];

    public function mount($role)
    {
        $this->role = $role;
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
        $this->clearSelectedRows();
    }

    public function updatedSelectedIsClosed()
    {
        $this->resetPage();
        $this->clearSelectedRows();
    }

    public function updatedSelectedCustomerOrMechanic()
    {
        $this->resetPage();
        $this->clearSelectedRows();
    }

    public function clearSelectedRows()
    {
        $this->selectedRows = [];
        $this->selectAll = false;
        $this->dispatch('rowsSelected', $this->selectedRows);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRows = $this->getCurrentPageInvoiceIds();
        } else {
            $this->selectedRows = [];
        }

        $this->dispatch('rowsSelected', $this->selectedRows);
    }

    protected function getCurrentPageInvoiceIds()
    {
        $query = $this->rows();

        return $query->pluck('id')
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
        $this->dispatch('rowsSelected', $this->selectedRows);
    }

    public function rows()
    {
        $query = Invoice::query();

        $query->whereHas('user.roles', function ($roleQuery) {
            $roleQuery->where('name', $this->role);
        });

        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->searchTerm}%")
                    ->orWhere('is_closed', 'like', "%{$this->searchTerm}%")
                    ->orWhere('iva', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('user', function ($userQuery) {
                        // Group the name and surname conditions together using a nested where
                        $userQuery->where(function ($userSubQuery) {
                            $userSubQuery->where('name', 'like', "%{$this->searchTerm}%")
                                         ->orWhere('surname', 'like', "%{$this->searchTerm}%");
                        });
                    })
                    ->orWhere('price', 'like', "%{$this->searchTerm}%");
            });
        }

        if (!empty($this->selectedState)) {
            $query->where('state', $this->selectedState);
        }

        if ($this->selectedIsClosed !== '') {
            $query->where('is_closed', $this->selectedIsClosed);
        }

        if ($this->selectedCustomerOrMechanic !== '') {
            $query->where('user_id', $this->selectedCustomerOrMechanic);
        }

        return $query;
    }

    public function updateIsClosed($invoiceId, $isClosed)
    {
        $invoice = Invoice::find($invoiceId);

        if ($invoice) {
            $invoice->is_closed = $isClosed;
            $invoice->save();
        }

        // Optional: Refresh the table or perform additional actions
        $this->resetPage(); // Reset pagination if needed
        $this->clearSelectedRows(); // Clear selection if needed
    }


    public function render()
    {
        $usersWithInvoices = User::with(['invoices', 'mechanicInfo', 'customerInfo'])
            ->whereHas('invoices')
            ->get();

        if ($this->role == 'mechanic') {
            $mechanics = $usersWithInvoices->filter(function($user) {
                return $user->mechanicInfo()->exists();
            });
            $customers = [];
        } else {
            $customers = $usersWithInvoices->filter(function($user) {
                return $user->customerInfo()->exists();
            });
            $mechanics = [];
        }

        return view('livewire.invoices-table', [
            'rows' => $this->rows()->paginate(12),
            'customers' => $customers,
            'mechanics' => $mechanics
        ]);
    }

}