<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;

class WorkstationMechanicTable extends Component
{
    public $mechanics;
    public $searchTerm = '';
    public $originalMechanics;

    public function mount(Collection $mechanics)
    {
        $this->originalMechanics = $mechanics;
        $this->mechanics = $mechanics;
    }

    public function render()
    {
        return view('livewire.workstation-mechanic-table', [
            'mechanics' => $this->mechanics,
        ]);
    }

    public function updatedSearchTerm()
    {
        $searchTerm = strtolower(trim($this->searchTerm));

        if (empty($searchTerm)) {
            $this->mechanics = $this->originalMechanics;
        } else {
            $this->mechanics = $this->originalMechanics->filter(function ($mechanic) use ($searchTerm) {
                $fullName = strtolower(trim($mechanic->user->name . ' ' . $mechanic->surname));
                return strpos($fullName, $searchTerm) !== false;
            });
        }
    }
}
