<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workstation extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'city', 'assigned_cars_count', 'address'];

    public function mechanics()
    {
        return $this->belongsToMany(MechanicInfo::class, 'mechanic_workstation', 'workstation_id', 'mechanic_id');
    }


    public function customer()
    {
        return $this->belongsTo(CustomerInfo::class, 'customer_id');
    }

    public function totalAssignedCars()
    {
        return $this->mechanics()->with('orders')->get()->sum(function ($mechanic) {
            return $mechanic->orders()->count();
        });
    }

    public function totalInProgressCars()
    {
        return $this->mechanics()->with('orders')->get()->sum(function ($mechanic) {
            return $mechanic->orders()->where('state', 'In lavorazione')->count();
        });
    }

    public function totalInQueueCars()
    {
        return $this->mechanics()->with('orders')->get()->sum(function ($mechanic) {
            return $mechanic->orders()->where('state', 'Nuova')->count();
        });
    }

}
