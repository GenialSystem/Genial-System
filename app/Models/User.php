<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory, HasRoles;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'cdf',
        'email',
        'password',
        'address',
        'cap',
        'cellphone',
        'city',
        'province',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // A user (customer) can have many orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function docs()
    {
        return $this->hasMany(GeneralDoc::class, 'user_id');
    }

    public function archives()
    {
        return $this->hasMany(Archive::class, 'user_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // A mechanic can belong to many orders
    // public function assignedOrders()
    // {
    //     return $this->belongsToMany(Order::class, 'order_mechanic', 'mechanic_id', 'order_id');
    // }

    public function customerInfo()
    {
        return $this->hasOne(CustomerInfo::class);
    }

    public function mechanicInfo()
    {
        return $this->hasOne(MechanicInfo::class);
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class)->withPivot('last_read_message_id');    
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.'. $this->id;
    }

    public function getFullName():string
    {
        return Auth::user()->name . ' ' . Auth::user()->surname;
    }

    public function notificationPreferences()
    {
        return $this->hasOne(NotificationPreference::class);
    }

}
