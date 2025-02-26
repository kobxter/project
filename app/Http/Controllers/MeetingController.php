<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        $query = Meeting::query();

        // Check if there's a search keyword
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                ->orWhereJsonContains('participants', $search);
        }

        // Order the meetings by the latest date (assuming 'meeting_date' is the date field)
        $meetings = $query->orderBy('created_at', 'desc') // 'desc' สำหรับการเรียงลำดับจากล่าสุดไปยังเก่าสุด
                  ->paginate(10);

        // Retrieve all users and meeting rooms for dropdowns
        $users = User::all();
        $meetingRooms = MeetingRoom::all();

        // Pass data to the view
        return view('staffwork', compact('meetings', 'users', 'meetingRooms'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id',
            'participant_count' => 'required|integer|min:1',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'meeting_room_id' => 'required|exists:meeting_rooms,id',
            'requester' => 'required|string|max:255',
            'chairperson' => 'required|string|max:255',
            'type' => 'required|in:open,closed',
            'department' => 'required|string|max:255',
        ]);

        // ตรวจสอบการซ้ำของห้องประชุมและช่วงเวลา
        $conflict = Meeting::where('meeting_room_id', $validatedData['meeting_room_id'])
            ->where(function ($query) use ($validatedData) {
                $query->whereBetween('start_datetime', [$validatedData['start_datetime'], $validatedData['end_datetime']])
                    ->orWhereBetween('end_datetime', [$validatedData['start_datetime'], $validatedData['end_datetime']])
                    ->orWhere(function ($query) use ($validatedData) {
                        $query->where('start_datetime', '<=', $validatedData['start_datetime'])
                                ->where('end_datetime', '>=', $validatedData['end_datetime']);
                    });
            })
            ->first();

        if ($conflict) {
            return redirect()->back()
                ->withInput($request->all())
                ->with('conflict', "วันที่-เวลา และห้องประชุมซ้ำกับการประชุม: '{$conflict->title}' 
                                    วันที่: {$conflict->start_datetime} ถึง {$conflict->end_datetime}");
        }

        $participants = $validatedData['type'] === 'closed' ? $validatedData['participants'] : [];

        // บันทึกข้อมูลการประชุม
        $meeting = Meeting::create([
            'title' => $validatedData['title'],
            'requester' => $validatedData['requester'],
            'chairperson' => $validatedData['chairperson'],
            'type' => $validatedData['type'],
            'department' => $validatedData['department'],
            'start_datetime' => $validatedData['start_datetime'],
            'end_datetime' => $validatedData['end_datetime'],
            'meeting_room_id' => $validatedData['meeting_room_id'],
            'participant_count' => $validatedData['participant_count'],
            'location' => $request->input('location', 'ห้องประชุมทั่วไป'),
            'participants' => $participants,
        ]);

        return redirect()->route('meetings.index')->with('success', 'บันทึกการประชุมสำเร็จ');
    }

    public function create()
    {
        $users = User::all();
        $meetingRooms = MeetingRoom::all();

        return view('meetings.create', compact('users', 'meetingRooms'));
    }

    public function edit($id)
    {
        $meeting = Meeting::findOrFail($id);
        $users = User::all();
        $meetingRooms = MeetingRoom::all();

        return view('meetings.edit', compact('meeting', 'users', 'meetingRooms'));
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'requester' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'chairperson' => 'required|string|max:255',
            'type' => 'required|in:open,closed',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'meeting_room_id' => 'required|exists:meeting_rooms,id',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id',
            'participant_count' => 'required|integer|min:1', // ใช้ชื่อที่ถูกต้อง
        ]);

        $conflict = Meeting::where('meeting_room_id', $validatedData['meeting_room_id'])
            ->where('id', '!=', $id)
            ->where(function ($query) use ($validatedData) {
                $query->whereBetween('start_datetime', [$validatedData['start_datetime'], $validatedData['end_datetime']])
                    ->orWhereBetween('end_datetime', [$validatedData['start_datetime'], $validatedData['end_datetime']])
                    ->orWhere(function ($query) use ($validatedData) {
                        $query->where('start_datetime', '<=', $validatedData['start_datetime'])
                            ->where('end_datetime', '>=', $validatedData['end_datetime']);
                    });
            })->first();

        if ($conflict) {
            return redirect()->back()->withErrors([
                'conflict' => "วันที่-เวลา และห้องประชุมซ้ำกับการประชุม: '{$conflict->title}' 
                                วันที่: {$conflict->start_datetime} ถึง {$conflict->end_datetime}",
            ]);
        }

        $meeting = Meeting::findOrFail($id);
        $meeting->update([
            'title' => $validatedData['title'],
            'requester' => $validatedData['requester'],
            'department' => $validatedData['department'],
            'chairperson' => $validatedData['chairperson'],
            'type' => $validatedData['type'],
            'start_datetime' => $validatedData['start_datetime'],
            'end_datetime' => $validatedData['end_datetime'],
            'meeting_room_id' => $validatedData['meeting_room_id'],
            'participants' => $validatedData['type'] == 'closed' ? $validatedData['participants'] : [], // Reset participants if open
            'participant_count' => $validatedData['participant_count'], // อัปเดตจำนวนผู้เข้าร่วม
        ]);

        return redirect()->route('meetings.index')->with('success', 'อัปเดตการประชุมสำเร็จ');
    }


    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);
        $title = $meeting->title;
        $meeting->delete();
        return redirect()->route('meetings.index')->with('success', 'ลบการประชุม: ' . $title . ' สำเร็จ');
    }
    public function roomSchedule()
    {
        $meetings = Meeting::with('meetingRoom')->get();
        // แปลงข้อมูลการประชุมเป็นรูปแบบ JSON สำหรับ FullCalendar
        $events = $meetings->map(function ($meeting) {
            return [
                'id' => $meeting->id,
                'title' => $meeting->title . ' (' . ($meeting->meetingRoom->name ?? 'ไม่ระบุ') . ')',
                'start' => $meeting->start_datetime,
                'end' => $meeting->end_datetime,
            ];
        });
        return view('welcome', ['events' => $events]);
    }
    public function getEvents()
    {
        $meetings = Meeting::with('meetingRoom')->get();
        $events = $meetings->map(function ($meeting) {
            return [
                'title' => $meeting->title . ' (' . ($meeting->meetingRoom->name ?? 'ไม่ระบุ') . ')',
                'start' => $meeting->start_datetime,
                'end' => $meeting->end_datetime,
            ];
        });
        return response()->json($events);
    }
    public function getEventsByDate(Request $request)
    {
        $date = $request->input('date');
        $events = Meeting::whereDate('start_datetime', $date)
            ->orWhereDate('end_datetime', $date)
            ->get()
            ->map(function ($meeting) {
                return [
                    'id' => $meeting->id,
                    'title' => $meeting->title . ' (' . ($meeting->meetingRoom->name ?? 'ไม่ระบุ') . ')',
                    'start' => $meeting->start_datetime,
                    'end' => $meeting->end_datetime,
                ];
            });

        return response()->json($events);
    }
    public function getAllEvents()
    {
        $events = Meeting::all()->map(function ($meeting) {
            $now = now(); // Current timestamp
            $status = $now->greaterThan($meeting->end_datetime) ? 'เสร็จสิ้น' : 'กำลังดำเนินการอยู่';

            return [
                'id' => $meeting->id,
                'title' => $meeting->title . ' (' . ($meeting->meetingRoom->name ?? 'ไม่ระบุ') . ')',
                'start' => $meeting->start_datetime->format('Y-m-d\TH:i:s'),
                'end' => $meeting->end_datetime ? $meeting->end_datetime->format('Y-m-d\TH:i:s') : null,
                'status' => $status,
                'color' => $status === 'เสร็จสิ้น' ? '#d9534f' : '#5bc0de', // Red for completed, blue for ongoing
            ];
        });

        return response()->json($events);
    }
    public function checkAvailability(Request $request)
    {
        $validatedData = $request->validate([
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
        ]);

        $availableRooms = MeetingRoom::whereDoesntHave('meetings', function ($query) use ($validatedData) {
            $query->where(function ($query) use ($validatedData) {
                $query->whereBetween('start_datetime', [$validatedData['start_datetime'], $validatedData['end_datetime']])
                    ->orWhereBetween('end_datetime', [$validatedData['start_datetime'], $validatedData['end_datetime']])
                    ->orWhere(function ($query) use ($validatedData) {
                        $query->where('start_datetime', '<=', $validatedData['start_datetime'])
                                ->where('end_datetime', '>=', $validatedData['end_datetime']);
                    });
            });
        })->get();

        return response()->json($availableRooms);
    }

}