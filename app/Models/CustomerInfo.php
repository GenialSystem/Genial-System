<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rag_sociale',
        'iva',
        'sdi',
        'user_id',
        'assigned_cars_count',
        'queued_cars_count',
        'finished_cars_count',
        'admin_name',
        'ragione_sociale',
        'iva',
        'pec',
        'legal_address',
    ];

    public function estimates()
    {
        return $this->hasMany(Estimate::class, 'customer_id');
    }

    public function docs()
    {
        return $this->hasMany(GeneralDoc::class, 'customer_id');
    }
    
    public function workstation()
    {
        return $this->hasOne(Workstation::class, 'customer_info_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
