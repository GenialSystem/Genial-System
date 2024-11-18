<?php
namespace App\Livewire;

use App\Models\Invoice;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class InvoicesTable extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $selectedRows = [];
    public $selectAll = false;
    public $selectedState = '';
    public $selectedIsClosed = '';
    public $selectedCustomerOrMechanic = '';
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
        // Get the current page invoice IDs
        $currentPageInvoiceIds = $this->getCurrentPageInvoiceIds();

        if ($value) {
            // Select all rows from the current page
            $this->selectedRows = array_unique(array_merge($this->selectedRows, $currentPageInvoiceIds));
        } else {
            // Deselect all rows from the current page
            $this->selectedRows = array_diff($this->selectedRows, $currentPageInvoiceIds);
        }

        $this->dispatch('rowsSelected', $this->selectedRows);
    }

    public function updatedPage()
    {
        // Get the current page invoice IDs
        $currentPageInvoiceIds = $this->getCurrentPageInvoiceIds();

        // Check if selectedRows contain all current page invoice IDs
        $this->selectAll = count(array_intersect($this->selectedRows, $currentPageInvoiceIds)) === count($currentPageInvoiceIds);
    }

    protected function getCurrentPageInvoiceIds()
    {
        // Retrieve the current paginated invoices
        return $this->rows()->pluck('id')->map(fn($id) => (string) $id)->toArray();
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

    public function rows()
    {
        $query = Invoice::query();

        // Filter by role
        $query->whereHas('user.roles', function ($roleQuery) {
            $roleQuery->where('name', $this->role);
        });

        // Search filtering
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->searchTerm}%")
                    ->orWhere('is_closed', 'like', "%{$this->searchTerm}%")
                    ->orWhere('iva', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('user', function ($userQuery) {
                        $userQuery->where(function ($userSubQuery) {
                            $userSubQuery->where('name', 'like', "%{$this->searchTerm}%")
                                         ->orWhere('surname', 'like', "%{$this->searchTerm}%");
                        });
                    })
                    ->orWhere('price', 'like', "%{$this->searchTerm}%");
            });
        }

        // State filtering
        if (!empty($this->selectedState)) {
            $query->where('state', $this->selectedState);
        }

        // Closed filtering
        if ($this->selectedIsClosed !== '') {
            $query->where('is_closed', $this->selectedIsClosed);
        }

        // Customer or mechanic filtering
        if ($this->selectedCustomerOrMechanic !== '') {
            $query->where('user_id', $this->selectedCustomerOrMechanic);
        }

        $query->orderBy('id', 'desc');

        return $query->paginate(12); // Add pagination directly here
    }

    public function updateIsClosed($invoiceId, $isClosed)
    {
        $invoice = Invoice::find($invoiceId);

        if ($invoice) {
            $invoice->is_closed = $isClosed;
            $invoice->save();
        }

        $this->resetPage(); // Reset pagination if needed
        $this->clearSelectedRows(); // Clear selection if needed
    }

    public function render()
    {
        return view('livewire.invoices-table', [
            'rows' => $this->rows(), // Updated to fetch paginated results
            'customers' => User::whereHas('customerInfo')->get(),
            'mechanics' => User::whereHas('mechanicInfo')->get(),
        ]);
    }

    public function paginationView()
    {
        return 'custom-pagination';
    }
}
