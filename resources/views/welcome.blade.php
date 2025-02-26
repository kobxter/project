@extends('layouts.app')
@section('content')
<style>
.modal-content {
    border-radius: 10px;
    overflow: hidden;
    font-size: 1rem; /* ปรับขนาดตัวอักษร */
}

.modal-header {
    padding: 1rem 1.5rem;
    background-color: #007bff; /* สี header */
    color: #fff; /* สีตัวอักษร */
}

.modal-body {
    padding: 1.5rem;
}

.modal-body p {
    margin-bottom: 1rem;
    font-size: 0.95rem;
    line-height: 1.5; /* เพิ่มความอ่านง่าย */
}

.modal-footer {
    padding: 1rem 1.5rem;
}

.modal-footer .btn {
    font-size: 0.9rem;
}
</style>
<div class="container">
</div>
<div class="container mt-5">
    <h1 class="text-center align-center">ปฏิทินการประชุม</h1>
    <br>
    <a href="{{ route('registrations.index') }}" class="btn btn-success">
    <i class="fa fa-sign-in-alt"></i> ลงทะเบียนเข้าร่วมประชุม</a>
    <div id="calendar"></div>
</div>
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0 rounded">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="eventDetailsModalLabel">
                    <i class="fas fa-calendar-alt"></i> รายละเอียดการประชุม
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="eventDetailsContent">
                    <!-- ข้อมูลรายละเอียดการประชุมจะแสดงที่นี่ -->
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> ปิด
                </button>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<!-- FullCalendar & jQuery -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/th.js"></script> <!-- Thai Localization -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');
    let calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'th', // Set the calendar to Thai
        initialView: 'dayGridMonth',
        editable: false,
        events: '/api/events', // Load events from API
        eventClick: function(info) {
            let event = info.event;
            let status = event.extendedProps.status;

            // Format วันที่และเวลา
            const formatDateTime = (date) => {
                return new Intl.DateTimeFormat('th-TH', {
                    weekday: 'long', // ชื่อวัน
                    year: 'numeric', // ปี
                    month: 'long', // เดือน
                    day: 'numeric', // วันที่
                    hour: '2-digit', // ชั่วโมง
                    minute: '2-digit', // นาที
                }).format(date) + ' น.'; // เพิ่ม "น." หลังเวลา
            };

            let content = `
                <p><strong>หัวข้อประชุม (สถานที่):</strong> ${event.title}</p>
                <p><strong>วันเวลาเริ่มต้น:</strong> ${formatDateTime(event.start)}</p>
                <p><strong>วันเวลาสิ้นสุด:</strong> ${event.end ? formatDateTime(event.end) : 'ไม่ระบุ'}</p>
                <p><strong>สถานะ:</strong> ${status}</p>
            `;
            $('#eventDetailsContent').html(content);
            $('#eventDetailsModal').modal('show');
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'วันนี้',
            month: 'เดือน',
            week: 'สัปดาห์',
            day: 'วัน'
        },
        navLinks: true, // Allow navigation
        dayMaxEvents: true, // Show "more" link when too many events
        eventContent: function(arg) {
            let timeText = arg.timeText ? `<div class="fc-event-time">${arg.timeText}</div>` : '';
            return {
                html: `
                    ${timeText}
                    <div class="fc-event-title">มีรายการจอง</div>
                `
            };
        }
    });

    calendar.render();
});
</script>

<style>
/* ปรับสีตัวหนังสือให้เข้ม และหัวตารางชัดเจน */
#calendar {
    margin: auto;
    color: #333;
    /* เข้มขึ้นสำหรับตัวหนังสือในตาราง */
    background-color: #fff;
    /* พื้นหลังสีขาว */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    /* ขอบโค้ง */
    padding: 15px;
    /* เพิ่มระยะห่าง */
}

/* หัวตารางของปฏิทิน */
.fc-toolbar {
    background-color: #444;
    /* สีพื้นหลังเข้ม */
    color: #fff;
    /* สีตัวหนังสือ */
    border-bottom: 1px solid #ddd;
    text-align: center;
}

/* ปรับสีของวันที่ในหัวตาราง */
.fc-col-header-cell {
    background-color: #444;
    /* สีพื้นหลังของวันในหัวตาราง */
    color: #fff;
    /* ตัวหนังสือสีขาว */
    font-weight: bold;
    padding: 10px;
    text-align: center;
}

/* ปรับลักษณะของ event */
.fc-event {
    color: #fff;
    /* สีตัวหนังสือใน event */
    background-color: #007bff;
    /* สีพื้นหลังของ event */
    border: none;
    /* ไม่มีขอบ */
}

/* ตัวหนังสือในปฏิทิน */
.fc-daygrid-day-number {
    color: #333;
    /* ตัวเลขในตาราง */
}

/* ปุ่มต่าง ๆ */
.fc-button {
    background-color: #444;
    /* สีปุ่ม */
    color: #fff;
    /* สีตัวหนังสือในปุ่ม */
    border: none;
}

.fc-button:hover {
    background-color: #666;
    /* สีเมื่อ hover ปุ่ม */
}
</style>


@endsection