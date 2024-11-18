<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'date', 'start_time', 'end_time', 'notify_me'];

    public function mechanics()
    {
        return $this->belongsToMany(MechanicInfo::class, 'event_mechanic', 'event_id', 'mechanic_id')
                    ->withPivot('confirmed')
                    ->withTimestamps();
    }
    
}
