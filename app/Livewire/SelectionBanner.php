<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class SelectionBanner extends Component
{
    public $selectedRows = [];
    public $actionButtons = [];
    public $showDeleteModal = false; // Track modal visibility
    public $modelClass; // The class of the model to delete

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

    public function deleteSelectedRows()
    {
        if (count($this->selectedRows) > 0) {
            // Set modal to visible
            $this->showDeleteModal = true;
        }
    }

    public function delete()
    {
        $modelClass = $this->modelClass;

        foreach ($this->selectedRows as $rowId) {
            $model = $modelClass::find($rowId);

            if ($model) {
                $model->delete();
            }
        }

        session()->flash('success', [
            'title' => 'Elementi eliminati con successo.',
            'subtitle' => 'Gli elementi selezionati sono stati eliminati.',
        ]);
        $this->clearSelection();
    }

    public function closeDeleteModal()
    {
        // Close the modal without doing anything
        $this->showDeleteModal = false;
    }

    public function openCustomModal()
    {
        // Logic for opening the edit modal can be implemented here
        $this->dispatch('openCustomModal', $this->selectedRows);
    }

    public function clearSelection()
    {
        $this->showDeleteModal = false;
        $this->selectedRows = [];
        $this->dispatch('selectionDeleted');
    }

    public function render()
    {
        return view('livewire.selection-banner');
    }
}
