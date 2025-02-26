<?php
namespace App\Http\Controllers;
use App\Models\Registration;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search'); // รับค่าช่องค้นหา
        $query = Meeting::with('registrations')
            ->where('end_datetime', '>=', now());
        
        // เพิ่มการค้นหาด้วยหัวข้อการประชุม
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        // แสดง 10 รายการต่อหน้า
        $meetings = $query->paginate(10);

        $userRegistrations = auth()->user()->registrations->pluck('meeting_id')->toArray();
        $users = User::all();

        return view('registrations.index', compact('meetings', 'userRegistrations', 'users', 'search'));
    }

    public function register(Request $request)
    {
        $meeting = Meeting::findOrFail($request->meeting_id);
        $userId = auth()->id();

        // ตรวจสอบว่าผู้ใช้มีชื่อในรายชื่อผู้เข้าร่วมประชุมหรือไม่
        if (!in_array($userId, $meeting->participants ?? [])) {
            return redirect()->back()->withErrors(['error' => 'คุณไม่มีรายชื่อในการประชุมนี้']);
        }

        // บันทึกการลงทะเบียน
        Registration::create([
            'user_id' => $userId,
            'meeting_id' => $request->meeting_id,
        ]);

        return redirect()->route('registrations.index')->with('success', 'ลงทะเบียนสำเร็จ');
    }

    public function requestLeave(Request $request, $meetingId)
    {
        $meeting = Meeting::findOrFail($meetingId);
        $userId = auth()->id();

        // เพิ่มสถานะการขอลาประชุม
        $registration = Registration::where('user_id', $userId)->where('meeting_id', $meetingId)->first();
        if (!$registration) {
            return redirect()->back()->withErrors(['error' => 'คุณยังไม่ได้ลงทะเบียนเข้าร่วมประชุม']);
        }

        $registration->update(['leave_requested' => true]);

        return redirect()->route('registrations.index')->with('success', 'ได้ส่งคำขอลาประชุมเรียบร้อยแล้ว');
    }

    public function approveLeave($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->leave_approved = true;
        $registration->save();

        return redirect()->route('registrations.manage')->with('success', 'การลาถูกอนุมัติเรียบร้อยแล้ว');
    }


    public function cancelRegistration($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->delete(); // ลบการลงทะเบียน
        return redirect()->route('registrations.manage')->with('success', 'การลงทะเบียนถูกยกเลิกแล้ว');
    }


    public function store(Request $request)
    {
        $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
        ]);
        // บันทึกการลงทะเบียน
        $registration = Registration::create([
            'user_id' => auth()->id(),
            'meeting_id' => $request->meeting_id,
        ]);
        // อัปเดตผู้เข้าร่วมใน meetings
        $meeting = Meeting::findOrFail($request->meeting_id);
        $participants = $meeting->participants ?? [];
        $participants[] = auth()->user()->name;
        $meeting->update(['participants' => $participants]);

        return redirect()->route('registrations.index')->with('success', 'ลงทะเบียนสำเร็จ');
    }
    public function manage(Request $request)
    {
        $search = $request->input('search');

        // Query สำหรับการค้นหา
        $registrations = Registration::with(['user', 'meeting'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
                })->orWhereHas('meeting', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // คำนวณสถิติการเข้าร่วมประชุม
        $normalCount = Registration::where('leave_requested', false)->count();
        $pendingLeaveCount = Registration::where('leave_requested', true)->where('leave_approved', false)->count();
        $approvedLeaveCount = Registration::where('leave_requested', true)->where('leave_approved', true)->count();

        return view('registrations.manage', compact(
            'registrations', 'normalCount', 'pendingLeaveCount', 'approvedLeaveCount'
        ));
    }
    public function exportPdf()
    {
        $registrations = Registration::with(['user', 'meeting'])->get();

        // คำนวณสถิติการเข้าร่วมประชุม
        $normalCount = $registrations->where('leave_requested', false)->count();
        $pendingLeaveCount = $registrations->where('leave_requested', true)->where('leave_approved', false)->count();
        $approvedLeaveCount = $registrations->where('leave_requested', true)->where('leave_approved', true)->count();

        // สร้าง PDF
        $pdf = Pdf::loadView('registrations.report', compact(
            'registrations', 'normalCount', 'pendingLeaveCount', 'approvedLeaveCount'
        ));

        return $pdf->download('รายงานผู้เข้าร่วมประชุม.pdf');
    }
    public function cancel($meetingId)
    {
        $user = auth()->user();
        $meeting = Meeting::findOrFail($meetingId);

        // ลบการลงทะเบียน
        Registration::where('user_id', $user->id)->where('meeting_id', $meetingId)->delete();

        // อัปเดตผู้เข้าร่วมใน meetings
        $participants = $meeting->participants ?? [];
        $participants = array_filter($participants, fn($participant) => $participant !== $user->name);
        $meeting->update(['participants' => $participants]);
        return redirect()->route('registrations.index')->with('success', 'ยกเลิกการลงทะเบียนสำเร็จ');
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'nullable|string',
        ]);
        $user = auth()->user();
        $user->update($request->only('email', 'phone'));
        return back()->with('success', 'ข้อมูลส่วนตัวถูกแก้ไขสำเร็จ');
    }
    public function exampleAction(Request $request)
    {
        // ตัวอย่างเมื่อดำเนินการสำเร็จ
        return redirect()->route('registrations.manage')->with('success', 'การดำเนินการเสร็จสมบูรณ์');

        // หรือเมื่อเกิดข้อผิดพลาด
        return redirect()->back()->withErrors(['เกิดข้อผิดพลาดในการดำเนินการ']);
    }

}