<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function images()
    {
        return $this->hasMany(OrderImage::class);
    }

    public function files()
    {
        return $this->hasMany(OrderFile::class);
    }

    public function carParts()
    {
        return $this->belongsToMany(CarPart::class, 'order_car_part')->withPivot('damage_count', 'paint_prep', 'replacement');
    }

    public function customer()
    {
        return $this->belongsTo(CustomerInfo::class, 'customer_id');
    }

    
    // public function customerInfo()
    // {
    //     // Access customer info through the customer (User model)
    //     return $this->hasOneThrough(CustomerInfo::class, User::class, 'id', 'user_id', 'customer_id', 'id');
    // }


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

    protected static function boot()
    {
        parent::boot();

        // Use the 'creating' or 'created' event
        static::deleting(function ($order) {
            // Define the folder path for the order (assuming it's `/orders/{id}`)
            $folderPath = 'public/orders/' . $order->id;
    
            if (Storage::exists($folderPath)) {
                Storage::deleteDirectory($folderPath);  // Delete the folder and all its contents
            }
    
        });
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
