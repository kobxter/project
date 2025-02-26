<?php
namespace App\Http\Controllers;

use App\Models\MeetingRoom;
use Illuminate\Http\Request;

class MeetingRoomController extends Controller
{
    public function index()
    {
        $meetingRooms = MeetingRoom::paginate(10);
        return view('meetingRooms.index', compact('meetingRooms'));
    }

    public function create()
    {
        return view('meetingRooms.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string',
        ]);

        MeetingRoom::create($validatedData);

        return redirect()->route('meetingRooms.index')->with('success', 'เพิ่มห้องประชุมสำเร็จ');
    }

    public function edit(MeetingRoom $meetingRoom)
    {
        return view('meetingRooms.edit', compact('meetingRoom'));
    }

    public function update(Request $request, MeetingRoom $meetingRoom)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string',
        ]);

        $meetingRoom->update($validatedData);

        return redirect()->route('meetingRooms.index')->with('success', 'แก้ไขห้องประชุมสำเร็จ');
    }

    public function destroy(MeetingRoom $meetingRoom)
    {
        $meetingRoom->delete();

        return redirect()->route('meetingRooms.index')->with('success', 'ลบห้องประชุมสำเร็จ');
    }
}
