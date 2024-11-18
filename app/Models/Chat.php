<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'name', 'order_id'];

    public function messages()
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('last_read_message_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest(); 
    }


}

