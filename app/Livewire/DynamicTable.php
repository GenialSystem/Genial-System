<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class DynamicTable extends Component
{
    use WithPagination;

    public $headers = [];
    public $actions = ['show', 'delete'];
    public $searchTerm = '';
    public $role = null;
    public $model;

    public function mount($model, $role = null)
    {
        $this->model = $model;
        $this->role = $role;
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = $this->model::query();

        if ($this->model == \App\Models\User::class && $this->role) {
            $query->role($this->role);
        }

        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                foreach ($this->headers as $field) {
                    $q->orWhere($field, 'like', "%{$this->searchTerm}%");
                }
            });
        }

        $rows = $query->paginate(12);
        // dd($rows);
        return view('livewire.dynamic-table', [
            'rows' => $rows,
        ]);
    }
}
