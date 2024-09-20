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
    public $hasInput = true;
    public $title;

    public function mount($model, $role = null, $hasInput = true)
    {
        $this->model = $model;
        $this->role = $role;
        $this->hasInput = $hasInput;
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
                    // If the field contains '->', handle nested relations
                    if (strpos($field, '->') !== false) {
                        list($relation, $attribute) = explode('->', $field);
                        $q->orWhereHas($relation, function ($query) use ($attribute) {
                            $query->where($attribute, 'like', "%{$this->searchTerm}%");
                        });
                    } else {
                        $q->orWhere($field, 'like', "%{$this->searchTerm}%");
                    }
                }
            });
        }

        $rows = $query->paginate(12);

        return view('livewire.dynamic-table', [
            'rows' => $rows,
        ]);
    }
}
