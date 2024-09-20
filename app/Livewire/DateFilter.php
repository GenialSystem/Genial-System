<?php

namespace App\Livewire;

use Livewire\Component;

class DateFilter extends Component
{
    public $dateFilter = '';

    protected $listeners = ['updateDate'];

    public function updatedDateFilter()
    {
        $this->dispatch('dateFilterUpdated', $this->dateFilter);
    }

    public function updateDate($date){
        $this->dateFilter = $date;
        $this->dispatch('dateFilterUpdated', $date);
    }

    public function render()
    {
        return view('livewire.date-filter');
    }
}
