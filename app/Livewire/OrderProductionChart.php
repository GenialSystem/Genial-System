<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Cookie;

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
        $monthsInItalian = $this->detectLanguage();

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

    private function detectLanguage()
    {
        if (isset($_COOKIE["googtrans"])) {
            $cookie = $_COOKIE["googtrans"];
        } else {
            $cookie = null;
        }

        return match($cookie){
            "/it/it" => [
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
            ],
            "/it/es" => [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre'
            ],
            "/it/fr" => [
                1 => 'Janvier',
                2 => 'Février',
                3 => 'Mars',
                4 => 'Avril',
                5 => 'Mai',
                6 => 'Juin',
                7 => 'Juillet',
                8 => 'Août',
                9 => 'Septembre',
                10 => 'Octobre',
                11 => 'Novembre',
                12 => 'Décembre'
            ],
            "/it/de" => [
                1 => 'Januar',
                2 => 'Februar',
                3 => 'März',
                4 => 'April',
                5 => 'Mai',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'August',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Dezember'
            ],
            "/it/en" => [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December'
            ],
            //default in italiano
            default => [
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
            ]
        };
    }
}
