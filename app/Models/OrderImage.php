<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderImage extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'image_path', 'disassembly'];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
