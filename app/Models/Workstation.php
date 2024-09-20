<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workstation extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id'];

    public function mechanics()
    {
        return $this->belongsToMany(MechanicInfo::class, 'mechanic_workstation', 'workstation_id', 'mechanic_id');
    }


    public function customer()
    {
        return $this->belongsTo(CustomerInfo::class, 'customer_id');
    }

}
