<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = ['mechanic_info_id', 'date', 'state', 'client_name'];
    use HasFactory;

    public function mechanicInfo()
    {
        return $this->belongsTo(MechanicInfo::class);
    }
}
