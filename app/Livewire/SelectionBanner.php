<?php
namespace App\Livewire;

use Livewire\Component;

class SelectionBanner extends Component
{
    public $selectedRows = [];
    public $actionButtons = [];
    public $modelClass; 

    protected $listeners = ['rowsSelected' => 'updateSelectedRows', 'updateSelectionBanner' => 'clearSelection'];

    public function mount($modelClass, $buttons)
    {
        $this->modelClass = $modelClass;
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

    public function render()
    {
        return view('livewire.selection-banner');
    }
}
