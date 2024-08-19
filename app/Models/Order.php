<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['plate', 'price', 'state', 'colour'];
    // Example accessor to format price for display
    public function getPriceAttribute($value)
    {
        return number_format($value, 2, ',', '.'); // Format price for display
    }

    // Example mutator to format price before saving to database
    public function setPriceAttribute($value)
    {
        $formattedValue = str_replace(['.', ','], ['', '.'], $value);
        $this->attributes['price'] = number_format($formattedValue, 2, '.', '');
    }
}
