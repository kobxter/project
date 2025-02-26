<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลการประชุมจากฐานข้อมูล
        $meetings = Meeting::with('meetingRoom')->get();

        // จัดเตรียมข้อมูลสำหรับ FullCalendar
        $events = $meetings->map(function ($meeting) {
            return [
                'title' => $meeting->title,
                'start' => $meeting->start_datetime,
                'end' => $meeting->end_datetime,
                'description' => $meeting->meetingRoom->name ?? 'ไม่ระบุห้องประชุม',
                'location' => $meeting->meetingRoom->location ?? 'ไม่ระบุ',
            ];
        });

        // ส่งข้อมูล meetings และ events ไปยัง view
        return view('welcome', [
            'meetings' => $meetings,
            'events' => $events,
        ]);
    }
}
