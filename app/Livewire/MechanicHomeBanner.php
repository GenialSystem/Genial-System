<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Order; // Assuming this is the Order model

class MechanicHomeBanner extends Component
{
    public $selectedYear;
    public $orderCountPerYear;

    public function mount()
    {
        // Default to the current year
        $this->selectedYear = now()->year;
        $this->fetchOrderCount(); // Initial fetch for current year
    }

    public function updatedSelectedYear($value)
    {
        $this->fetchOrderCount(); // Fetch when the selected year changes
    }

    public function fetchOrderCount()
    {
        // Fetch the number of orders for the authenticated mechanic
        $this->orderCountPerYear = Auth::user()->mechanicInfo->repaired_count;
            
    }

    public function render()
    {
        return view('livewire.mechanic-home-banner');
    }
}
