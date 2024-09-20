<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'state',
        'color',
        'plate',
        'price',
        'brand',
        'notes',
        'car_size',
        'replacements',
        'aluminium',
        'assembly_disassembly',
        'damage_diameter',
        'customer_id',
        'earn_mechanic_percentage'
    ];

    public function carParts()
    {
        return $this->belongsToMany(CarPart::class, 'order_car_part')->withPivot('damage_count', 'paint_prep', 'replacement');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // In the Order model (Order.php)
    public function customerInfo()
    {
        // Access customer info through the customer (User model)
        return $this->hasOneThrough(CustomerInfo::class, User::class, 'id', 'user_id', 'customer_id', 'id');
    }


    // An order has many mechanics
    public function mechanics()
    {
        return $this->belongsToMany(User::class, 'order_mechanic', 'order_id', 'mechanic_id');
    }
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
