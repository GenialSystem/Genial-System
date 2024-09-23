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

            // Combine the id and year (e.g., "2/2024")
            $invoice->number = $invoice->id . '/' . $year;

            // Save the updated model
            $invoice->save();
        });
    }
}
