<?php

namespace App\Livewire;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class WorkChart extends Component
{
    private function getOrdersCountForLast20Days()
    {
        $counts = [];
        $labels = ['5gg', '10gg', '15gg', '20gg']; // Labels for the intervals
        $intervals = [5, 10, 15, 20]; // The specific intervals we're interested in
    
        foreach ($intervals as $days) {
            // Calculate the start date for the interval
            $startDate = Carbon::today()->subDays($days); // Start date is 'days' ago
            // End date is today since we want all records created up to today
            $endDate = Carbon::today(); 
    
            Log::info('Checking orders between: ' . $startDate->toDateString() . ' and ' . $endDate->toDateString());
    
            // Count orders within the specific interval
            $count = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('customer_id', 1)
                ->count();
    
            // Log the result for debugging
            Log::info('Count for interval ' . $days . ' days: ' . $count);
            $counts[] = $count; // Add count to the array
        }
    
        return [
            'counts' => $counts, // Return only the relevant counts
            'labels' => $labels, // Return only the specified labels
        ];
    }
    
    
    
    public function render()
    {
        // Get orders from the last 20 days
        $data = $this->getOrdersCountForLast20Days();

        return view('livewire.work-chart', [
            'orderCounts' => $data['counts'],
            'labels' => $data['labels'],
        ]);
    }
}
