<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;
    
    protected $fillable = ['type', 'price'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
