<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'requester',
        'chairperson',
        'type',
        'department',
        'participants',
        'start_datetime',
        'end_datetime',
        'location',
        'meeting_room_id', // เพิ่ม meeting_room_id
        'participant_count', // เพิ่ม participant_count
    ];
    
    protected $casts = [
        'participants' => 'array',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];
    
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
    
    public function meetingRoom()
    {
        return $this->belongsTo(MeetingRoom::class);
    }
}
