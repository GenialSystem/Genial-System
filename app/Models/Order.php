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
        'payment',
        'replacements',
        'aluminium',
        'assembly_disassembly',
        'damage_diameter',
        'customer_id',
        'earn_mechanic_percentage'
    ];

    // protected $with = ['customer'];

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

    public function getPriceAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }

    // An order has many mechanics
    public function mechanics()
    {
        return $this->belongsToMany(MechanicInfo::class, 'order_mechanic', 'order_id', 'mechanic_id');
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
