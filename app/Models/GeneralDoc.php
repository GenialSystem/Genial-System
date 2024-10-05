<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralDoc extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(CustomerInfo::class);
    }
}
