<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'name', 'order_id'];
    protected $with = ['users', 'latestMessage'];
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

    protected static function boot()
    {
        parent::boot();

        // Use the 'creating' or 'created' event
        static::deleting(function ($chat) {
            // Define the folder path for the chat (assuming it's `/chats/{id}`)
            $folderPath = 'public/chats/' . $chat->id;
    
            if (Storage::exists($folderPath)) {
                Storage::deleteDirectory($folderPath);  // Delete the folder and all its contents
            }
    
        });
    }
}

