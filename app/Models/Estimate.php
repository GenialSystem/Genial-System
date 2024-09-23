<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;
    
    protected $fillable = ['type', 'price', 'number', 'customer_id'];

    public function customer()
    {
        return $this->belongsTo(CustomerInfo::class, 'customer_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Use the 'creating' or 'created' event
        static::created(function ($estimate) {
            // Get the current year
            $year = now()->year;

            // Combine the id and year (e.g., "2/2024")
            $estimate->number = $estimate->id . '/' . $year;

            // Save the updated model
            $estimate->save();
        });
    }
}
