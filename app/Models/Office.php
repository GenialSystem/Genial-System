<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'path', 'parent_id'];

    public function children()
    {
        return $this->hasMany(Office::class, 'parent_id', 'id');
    }

}
