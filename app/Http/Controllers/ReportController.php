<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\User;
use App\Models\Meeting;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function select()
    {
        $users = User::all();
        $meetings = Meeting::all();
        return view('reports.select', compact('users', 'meetings'));
    }

    public function export(Request $request)
    {
        $query = Registration::with(['user', 'meeting']);

        // กรองข้อมูลตามตัวเลือกที่ผู้ใช้เลือก
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->meeting_id) {
            $query->where('meeting_id', $request->meeting_id);
        }
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        if ($request->leave_status) {
            if ($request->leave_status == 'normal') {
                $query->where('leave_requested', false);
            } elseif ($request->leave_status == 'pending') {
                $query->where('leave_requested', true)->where('leave_approved', false);
            } elseif ($request->leave_status == 'approved') {
                $query->where('leave_requested', true)->where('leave_approved', true);
            }
        }

        $registrations = $query->get();

        // คำนวณสถิติ
        $normalCount = $registrations->where('leave_requested', false)->count();
        $pendingLeaveCount = $registrations->where('leave_requested', true)->where('leave_approved', false)->count();
        $approvedLeaveCount = $registrations->where('leave_requested', true)->where('leave_approved', true)->count();

        if ($request->action == 'view') {
            return view('registrations.report', compact('registrations', 'normalCount', 'pendingLeaveCount', 'approvedLeaveCount'));
        }

        // สร้าง PDF
        $pdf = Pdf::loadView('registrations.report', compact('registrations', 'normalCount', 'pendingLeaveCount', 'approvedLeaveCount'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions(['defaultFont' => 'THSarabunNew']);

        return $pdf->download('รายงานผู้เข้าร่วมประชุม.pdf');
    }
}
