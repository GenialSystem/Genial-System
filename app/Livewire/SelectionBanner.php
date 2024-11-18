<?php
namespace App\Livewire;

use App\Models\Estimate;
use App\Notifications\EstimateStateChanged;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SelectionBanner extends Component
{
    public $selectedRows = [];
    public $actionButtons = [];
    public $modelClass; 
    public $modelName;

    protected $listeners = ['rowsSelected' => 'updateSelectedRows', 'updateSelectionBanner' => 'clearSelection', 'refresh' => '$refresh'];

    public function mount($modelClass, $buttons, $modelName)
    {
        $this->modelClass = $modelClass;
        $this->modelName = $modelName;

        $this->actionButtons = $buttons;
    }

    public function updateSelectedRows($rows)
    {
        $this->selectedRows = $rows;
    }

    public function clearSelection()
    {
        $this->selectedRows = [];
        $this->dispatch('selectionDeleted');
    }
    
    public function downloadPdfs($model, $ids)
    {
        // Convert the comma-separated IDs to an array
        $ids = implode(',', $ids);

        $this->clearSelection();
        $this->selectedRows = [];

        $this->dispatch('refresh');
        
        return redirect()->route('downloadPDF', ['model' => $model, 'ids' => $ids]);
    }

    public function applyStateToSelectedRows()
    {
        if (!empty($this->selectedRows)) {
           $estimates = Estimate::whereIn('id', $this->selectedRows)->get();
           foreach ($estimates as $estimate) {
           if($estimate->state == 'Archiviato')
            {
                continue;
            }
            $estimate->update(['state' => 'Archiviato']);
            // $creator = Auth::user()->getFullName();
            // $estimate->customer->user->notify(new EstimateStateChanged($creator, $estimate));
           }
            $this->dispatch('updateSelectionBanner');
            $this->dispatch('closeModal');
        }
    }

    public function render()
    {
        return view('livewire.selection-banner');
    }
}
