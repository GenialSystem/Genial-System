<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrderProductionChart extends Component
{
    public $months = [];
    public $orderCounts = [];
    public $selectedYear;

    protected $listeners = ['updateYear'];

    public function mount()
    {
        $this->selectedYear = Carbon::now()->year; // Default to the current year
        $this->updateChartData($this->selectedYear);
    }

    public function updateYear($year)
    {
        $this->selectedYear = $year;
        $this->updateChartData($this->selectedYear);        // Log::info(Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        // ->whereYear('created_at', $year) // Filter by the selected year
        // ->groupBy('month')
        // ->orderBy('month')
        // ->pluck('count', 'month')->toArray());
    }

    public function updateChartData($year)
    {
        $monthsInItalian = [
            1 => 'Gennaio',
            2 => 'Febbraio',
            3 => 'Marzo',
            4 => 'Aprile',
            5 => 'Maggio',
            6 => 'Giugno',
            7 => 'Luglio',
            8 => 'Agosto',
            9 => 'Settembre',
            10 => 'Ottobre',
            11 => 'Novembre',
            12 => 'Dicembre'
        ];

        // Initialize the month names in Italian
        $this->months = collect(range(1, 12))->map(function ($month) use ($monthsInItalian) {
            return $monthsInItalian[$month];
        })->toArray(); // Ensure this is an array

        // Get the number of orders for each month in the selected year
        $ordersByMonth = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year) // Filter by the selected year
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')->toArray(); // Convert to array

        $finalOrders = $this->orderCounts = collect(range(1, 12))->map(function ($month) use ($ordersByMonth) {
            return $ordersByMonth[$month] ?? 0; // Use null coalescing to safely access the month count or default to 0
        })->toArray();
            $this->dispatch('chartDataUpdated', [
                'months' => $this->months,
                'orderCounts' => $finalOrders
            ]);
    }

    public function render()
    {
        return view('livewire.order-production-chart');
    }
}