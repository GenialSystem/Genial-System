<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MechanicInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'plain_password',
        'address',
        'cap',
        'cdf',
        'user_id',
        'repaired_count',
        'working_count',
        'city',
        'cellphone',
        'surname',
        'province',
        'branch',
    ];
    
    public function workstations()
    {
        return $this->belongsToMany(Workstation::class, 'mechanic_workstation', 'mechanic_info_id', 'workstation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_mechanic', 'mechanic_id', 'event_id')
                    ->withPivot('confirmed')
                    ->withTimestamps();
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }
    
}
