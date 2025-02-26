<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meeting_id',
        'leave_requested',
        'leave_approved', // เพิ่มฟิลด์นี้
    ];
    
    protected $casts = [
        'leave_requested' => 'boolean',
        'leave_approved' => 'boolean', // กำหนดเป็น boolean
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}

