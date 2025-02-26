<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลเหตุการณ์ทั้งหมด
        return Event::all();
    }

    public function store(Request $request)
    {
        // เพิ่มเหตุการณ์ใหม่
        $event = Event::create([
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return response()->json($event, 201);
    }

    public function update(Request $request, $id)
    {
        // อัปเดตเหตุการณ์
        $event = Event::findOrFail($id);
        $event->update([
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return response()->json($event, 200);
    }

    public function destroy($id)
    {
        // ลบเหตุการณ์
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(null, 204);
    }
}
