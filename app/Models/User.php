<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'first_name', 
        'last_name', 
        'phone',
        'position',
        'name',
        'email',
        'password',
        'role',
        'title', 
        'nickname', 
        'department', 
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [ // เปลี่ยนจาก function เป็น property
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
