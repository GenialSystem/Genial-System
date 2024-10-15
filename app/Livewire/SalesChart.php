<?php

namespace App\Livewire;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SalesChart extends Component
{
    public $startDate;
    public $endDate;
    public $salesData = [];

    protected $listeners = ['updateSalesChart'];

    public function mount()
    {
        // Set start date to the first day of the previous month
        $this->startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        // Set end date to the last day of the current month
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        
        // Fetch initial sales data for the selected range
        $this->salesData = $this->getSalesDataForRange($this->startDate, $this->endDate);
    }


    public function updateSalesChart($start, $end)
    {
        // Create start and end dates from month values
        $year = Carbon::now()->year; // Assuming you're using the current year

        // Assuming $start and $end are month numbers (e.g., '01', '02')
        $this->startDate = Carbon::createFromFormat('Y-m', "$year-$start")->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::createFromFormat('Y-m', "$year-$end")->endOfMonth()->format('Y-m-d');

        // Fetch sales data for the new date range
        $this->salesData = $this->getSalesDataForRange($this->startDate, $this->endDate);

        // Dispatch updated sales data to the frontend
        $this->dispatch('salesChartUpdated', $this->formatSalesDataForJavaScript());
    }

    private function getSalesDataForRange($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->endOfMonth();
        $months = [];

        // Generate an array of months between the start and end date
        while ($start->lessThanOrEqualTo($end)) {
            $months[] = $start->copy();
            $start->addMonth();
        }

        // Retrieve sales data for each month in the range
        $salesData = [];
        foreach ($months as $month) {
            $salesData[$month->format('Y-m')] = $this->getSalesDataForMonth($month);
        }
       
        
        return $salesData;
    }

   
    private function getSalesDataForMonth($date)
    {
        $startOfMonth = $date->startOfMonth()->format('Y-m-d');
        $endOfMonth = $date->endOfMonth()->format('Y-m-d');
    
        // Fetch sales data from the orders where state is "Riparata" and group by periods (5, 10, 15, etc.)
        $salesData = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('state', 'Riparata')
            ->selectRaw('
                CASE
                    WHEN DATEDIFF(created_at, ?) <= 5 THEN "5gg"
                    WHEN DATEDIFF(created_at, ?) <= 10 THEN "10gg"
                    WHEN DATEDIFF(created_at, ?) <= 15 THEN "15gg"
                    WHEN DATEDIFF(created_at, ?) <= 20 THEN "20gg"
                    WHEN DATEDIFF(created_at, ?) <= 25 THEN "25gg"
                    ELSE "30gg"
                END AS period,
                SUM(price) AS total_price
            ', [$startOfMonth, $startOfMonth, $startOfMonth, $startOfMonth, $startOfMonth])
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total_price', 'period')
            ->toArray();
    
        // Ensure all periods (5gg, 10gg, etc.) are accounted for
        $periods = ["5gg", "10gg", "15gg", "20gg", "25gg", "30gg"];
        $data = array_fill_keys($periods, 0);
        $data = array_merge($data, $salesData);
    
        return array_values($data);
    }
    
    


    private function formatSalesDataForJavaScript()
    {
        // Prepare periods and sales data in the correct format for the chart
        $periods = ["5gg", "10gg", "15gg", "20gg", "25gg", "30gg"];
        $salesData = [];
        $maxPrice = 0; // Initialize the max price variable

        foreach ($this->salesData as $month => $data) {
            $salesData[$month] = array_pad($data, count($periods), 0); // Ensure all periods are included
            
            // Find the highest price in the data
            $monthMax = max($data);
            if ($monthMax > $maxPrice) {
                $maxPrice = $monthMax;
            }
        }

        // Return the periods, sales data, and the maximum price for the frontend
        return [
            'periods' => $periods,
            'salesData' => $salesData,
            'maxPrice' => $maxPrice // Pass the maximum price
        ];
    }


    public function render()
    {
        // Pass the initial formatted sales data to the frontend
        return view('livewire.sales-chart', [
            'initialData' => $this->formatSalesDataForJavaScript()
        ]);
    }
}
