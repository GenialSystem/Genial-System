<?php

namespace App\Models;

use App\Notifications\EstimateStateChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        
            // Find the highest `number` for this year in a safe way
            $lastNumber = static::whereYear('created_at', $year)
                ->orderByRaw('CAST(SUBSTRING_INDEX(number, "/", 1) AS UNSIGNED) DESC')
                ->value('number');
        
            if ($lastNumber) {
                // Extract the numeric part before the `/` and increment
                $lastSequence = (int)explode('/', $lastNumber)[0];
                $estimate->number = ($lastSequence + 1) . '/' . $year;
            } else {
                // Start from 1 if no records for the year
                $estimate->number = '1/' . $year;
            }
        });
        

        static::updated(function ($estimate) {
            if ($estimate->customer && $estimate->state != 'Nuovo') {
                $editor = Auth::user()->getFullName();
                $estimate->customer->user->notify(new EstimateStateChanged($editor, $estimate));
            }
        });
    }

    

}
