<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;
    
    protected $fillable = ['type', 'price', 'number', 'customer_id', 'state', 'mechanic_id', 'brand', 'plate'];

    public function customer()
    {
        return $this->belongsTo(CustomerInfo::class, 'customer_id');
    }

    public function mechanic()
    {
        return $this->belongsTo(MechanicInfo::class, 'mechanic_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($estimate) {
            // Get the current year
            $year = now()->year;

            // Find the last estimate created this year
            $lastEstimate = static::whereYear('created_at', $year)->latest()->first();

            // If there is no estimate for this year, start from 1, otherwise increment the last number
            if ($lastEstimate) {
                // Extract the number part (before the slash) and increment it
                $lastNumber = (int) explode('/', $lastEstimate->number)[0];
                $estimate->number = ($lastNumber + 1) . '/' . $year;
            } else {
                // Start with "1/year" if this is the first estimate of the year
                $estimate->number = '1/' . $year;
            }
        });
    }
}
