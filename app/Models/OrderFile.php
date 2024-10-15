<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderFile extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'file_path', 'file_type', 'name'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
