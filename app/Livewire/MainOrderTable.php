<?php

namespace App\Livewire;

use App\Models\MechanicInfo;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Notifications\OrderFinished;
use Illuminate\Support\Facades\Auth;

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

    protected $listeners = [
        'selectionDeleted' => 'clearSelectedRows',
        'dateFilterUpdated' => 'handleDateFilterUpdated',
        'paginationUpdated' => 'resetSelectAll',
    ];
    
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

    public function updatedPage()
    {
        // Get the current page order IDs
        $currentPageOrderIds = $this->getCurrentPageOrderIds();

        // Check if selectedRows contain all current page order IDs
        $this->selectAll = !array_diff($currentPageOrderIds, $this->selectedRows);
    }

    public function updateState($orderId, $newState)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->state = $newState;
            $order->save();
            if ($newState == 'Riparata' && $order->customer) {
                $this->handleOrderFinish($order);
            }
        }
    }

    private function handleOrderFinish($order)
    {
        $creator = Auth::user()->getFullName();

        $order->customer->user->notify(new OrderFinished($creator, $order->id));
        
        if($order->finish_date == null){
            $order->customer->increment('finished_cars_count');
            
            foreach ($order->mechanics as $mechanic) {
                $mechanic->increment('repaired_count');
            }
        }
        $order->finish_date = now();
        $order->update();
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
        // Ottieni gli ID delle righe della pagina corrente
        $currentPageRows = $this->getCurrentPageOrderIds();

        if ($value) {
            // Seleziona tutte le righe della pagina corrente
            $this->selectedRows = array_unique(array_merge($this->selectedRows, $currentPageRows));
        } else {
            // Deseleziona tutte le righe della pagina corrente
            $this->selectedRows = array_diff($this->selectedRows, $currentPageRows);
        }

        $this->dispatch('rowsSelected', $this->selectedRows);
    }


    protected function getCurrentPageOrderIds()
    {
        // Usa la paginazione per ottenere gli ordini della pagina corrente
        $orders = Order::query()
            ->when(!empty($this->searchTerm), function ($q) {
                // Applica filtri di ricerca se presenti
                $q->where(function ($q) {
                    $q->where('id', 'like', "%{$this->searchTerm}%")
                        ->orWhere('state', 'like', "%{$this->searchTerm}%")
                        ->orWhere('color', 'like', "%{$this->searchTerm}%")
                        ->orWhere('plate', 'like', "%{$this->searchTerm}%")
                        ->orWhere('price', 'like', "%{$this->searchTerm}%")
                        ->orWhereHas('customer', function ($customerQuery) {
                            $customerQuery->where('admin_name', 'like', "%{$this->searchTerm}%")
                                ->orWhereHas('user', function ($userQuery) {
                                    $userQuery->where('city', 'like', "%{$this->searchTerm}%")
                                        ->orWhere('name', 'like', "%{$this->searchTerm}%")
                                        ->orWhere('surname', 'like', "%{$this->searchTerm}%"); // Modifica: "name" viene cercato in "users"
                                });
                        })
                        ->orWhereHas('mechanics', function ($mechanicQuery) {
                            $mechanicQuery->whereHas('user', function ($userQuery) {
                                $userQuery->where('name', 'like', "%{$this->searchTerm}%")
                                ->orWhere('surname', 'like', "%{$this->searchTerm}%"); // Modifica: "name" in relazione con "users"
                            });
                        });
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(12); // Recupera la paginazione

        // Prendi solo gli ID della pagina corrente
        return $orders->pluck('id')->map(fn($id) => (string) $id)->toArray();
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

        // Se il flag booleano è vero, recupera gli ordini assegnati all'utente autenticato (cliente)
        if ($this->isCustomer) {
            $query->where('customer_id', Auth::user()->customerInfo->id);
        }

        // Applica il filtro di ricerca
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->searchTerm}%")
                    ->orWhere('state', 'like', "%{$this->searchTerm}%")
                    ->orWhere('color', 'like', "%{$this->searchTerm}%")
                    ->orWhere('plate', 'like', "%{$this->searchTerm}%")
                    ->orWhere('price', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('customer', function ($customerQuery) {
                        $customerQuery->where('admin_name', 'like', "%{$this->searchTerm}%")
                            ->orWhereHas('user', function ($userQuery) {
                                $userQuery->where('city', 'like', "%{$this->searchTerm}%")
                                    ->orWhere('name', 'like', "%{$this->searchTerm}%")
                                    ->orWhere('surname', 'like', "%{$this->searchTerm}%"); // Modifica: "name" viene cercato in "users"
                            });
                    })
                    ->orWhereHas('mechanics', function ($mechanicQuery) {
                        $mechanicQuery->whereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'like', "%{$this->searchTerm}%")
                            ->orWhere('surname', 'like', "%{$this->searchTerm}%"); // Modifica: "name" in relazione con "users"
                        });
                    });
            });
        }

        // Applica il filtro di data
        if (!empty($this->dateFilter)) {
            $query->whereDate('created_at', '=', $this->dateFilter);
        }

        // Applica il filtro del meccanico
        if (!empty($this->selectedMechanic)) {
            $query->whereHas('mechanics', function ($mechanicQuery) {
                $mechanicQuery->where('users.id', '=', $this->selectedMechanic);
            });
        }

        $query->orderBy('id', 'desc');

        return view('livewire.main-order-table', [
            'rows' => $query->paginate(12),  // Paginazione dei risultati
            'mechanics' => MechanicInfo::all(),  // Fornisci i meccanici per il filtro a discesa
        ]);
    }

    public function paginationView()
    {
        return 'custom-pagination';
    }
}
