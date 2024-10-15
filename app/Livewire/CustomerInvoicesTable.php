<?php

namespace App\Livewire;

use App\Models\Invoice;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerInvoicesTable extends Component
{
    use WithPagination;

    public $searchTerm = '';
  
    public $selectedState = ''; // For state filter
    public $selectedIsClosed = ''; // For is_closed filter
    public $selectedCustomerOrMechanic = ''; // For customer or mechanic filter
    public $invoices;

    public function mount($invoices)
    {  
        $this->invoices = $invoices;
    }

    public function updateIsClosed($invoiceId, $isClosed)
    {
        $invoice = Invoice::find($invoiceId);

        if ($invoice) {
            $invoice->is_closed = $isClosed;
            $invoice->save();
        }
    }

    public function render()
    {
        $query = Invoice::query()
        ->whereIn('id', $this->invoices->pluck('id')) // Filter invoices passed via mount
        ->when(!empty($this->searchTerm), function($q) {
            $q->where(function ($q) {
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
        })
        ->when(!empty($this->selectedState), function($q) {
            // Apply state filter when selected
            $q->where('state', $this->selectedState);
        })
        ->when($this->selectedIsClosed !== '', function($q) {
            // Apply is_closed filter when selected
            $q->where('is_closed', $this->selectedIsClosed);
        });

        return view('livewire.customer-invoices-table', [
            'rows' => $query->paginate(12)
        ]);
    }
    
    public function paginationView()
    {
        return 'custom-pagination';
    }
}
