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
        
            // Find the last invoice created this year
            $lastInvoice = static::whereYear('created_at', $year)->latest()->first();
        
            // If there is no invoice for this year, start from 1; otherwise, increment the last number
            if ($lastInvoice) {
                // Extract the number part (before the slash) and increment it
                $lastNumber = (int) explode('/', $lastInvoice->number)[0];
                $invoice->number = ($lastNumber + 1) . '/' . $year;
            } else {
                // Start with "1/year" if this is the first invoice of the year
                $invoice->number = '1/' . $year;
            }
        
            // Save the updated model
            $invoice->saveQuietly(); // Use saveQuietly() to avoid triggering other events again
        });
        
    }
}
