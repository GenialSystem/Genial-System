<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model 
{
    use HasFactory;
    protected $casts = [
        'id' => 'integer',
        'chat_id' => 'integer',
        'user_id' => 'integer',
    ];
    protected $fillable = ['chat_id', 'user_id', 'content', 'file_path'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
