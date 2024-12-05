<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    
    protected $fillable = ['is_closed', 'user_id', 'price', 'iva', 'number'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Use the 'creating' or 'created' event
        static::created(function ($invoice) {
            // Get the current year
            $year = now()->year;
        
            // Find the highest number for this year safely
            $lastNumber = static::whereYear('created_at', $year)
                ->orderByRaw('CAST(SUBSTRING_INDEX(number, "/", 1) AS UNSIGNED) DESC')
                ->value('number');
        
            if ($lastNumber) {
                // Extract the numeric part before the '/' and increment
                $lastSequence = (int) explode('/', $lastNumber)[0];
                $invoice->number = ($lastSequence + 1) . '/' . $year;
            } else {
                // Start with "1/year" if there are no invoices for the year
                $invoice->number = '1/' . $year;
            }
        
            // Save the updated `number` field
            $invoice->saveQuietly(); // Prevent recursive event triggering
        });
        
        
    }
}
