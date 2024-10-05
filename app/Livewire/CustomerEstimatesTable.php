<?php

namespace App\Livewire;

use App\Models\Estimate;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerEstimatesTable extends Component
{
    use WithPagination;

    public $estimates;
    public $searchTerm = '';
    public $selectedState = '';

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

    protected $queryString = ['searchTerm', 'selectedState'];

    public function mount($estimates)
    {
        $this->estimates = $estimates;
    }

    public function updateState($estimateId, $newState)
    {
        $estimate = Estimate::find($estimateId);
        if ($estimate) {
            $estimate->state = $newState;
            $estimate->update();
        }
    }

    public function render()
    {
        $query = Estimate::query()
            ->whereIn('id', $this->estimates->pluck('id')) // Filter estimates passed via mount
            ->when(!empty($this->searchTerm), function($q) {
                $q->where(function ($q) {
                    $q->where('id', 'like', "%{$this->searchTerm}%")
                        ->orWhere('state', 'like', "%{$this->searchTerm}%")
                        ->orWhere('type', 'like', "%{$this->searchTerm}%")
                        ->orWhere('price', 'like', "%{$this->searchTerm}%")
                        ->orWhereHas('customer', function ($userQuery) {
                            $userQuery->where('id', 'like', "%{$this->searchTerm}%")
                                ->orWhereHas('user', function ($infoQuery) {
                                    $infoQuery->where('name', 'like', "%{$this->searchTerm}%")
                                            ->orWhere('surname', 'like', "%{$this->searchTerm}%");
                                });
                        });
                });
            })
            ->when(!empty($this->selectedState), function($q) {
                // Apply state filter when selected
                $q->where('state', $this->selectedState);
            });

        // Paginate the results
        return view('livewire.customer-estimates-table', [
            'rows' => $query->paginate(12),
        ]);
    }

}
