<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    
    protected $fillable = ['is_closed'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
