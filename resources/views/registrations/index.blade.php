@extends('layouts.app')

@section('content')
<style>
.table thead th {
    background-color: rgb(11, 172, 73);
    /* สีเขียว */
    color: white;
    /* ตัวอักษรสีขาว */
    text-align: center;
    /* จัดข้อความให้อยู่กึ่งกลาง */
}
</style>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertMessage = "{{ session('success') }}";
    document.getElementById('alertMessage').innerText = alertMessage;
    new bootstrap.Modal(document.getElementById('alertModal')).show();
});
</script>
@endif

@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertMessage = "{{ $errors->first() }}";
    document.getElementById('alertMessage').innerText = alertMessage;
    new bootstrap.Modal(document.getElementById('alertModal')).show();
});
</script>
@endif


<div class="container">
    <h1>ลงทะเบียนเข้าร่วมประชุม</h1>

    <!-- ค้นหา -->
    <form action="{{ route('registrations.index') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="ค้นหาหัวข้อการประชุม..."
                    value="{{ $search ?? '' }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </div>
    </form>
    <!-- Modal สำหรับแจ้งเตือน -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">การแจ้งเตือน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="alertMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-lg">
        <div class="card-body ">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ลำดับที่</th>
                        <th>หัวข้อการประชุม</th>
                        <th>วันที่ - เวลา</th>
                        <th>สถานที่</th>
                        <th>ผู้ขอจอง</th>
                        <th>การลงทะเบียน</th>
                        <th>การขอลา</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $meeting)
                    <tr>
                        <td class="text-center align-center">{{ $loop->iteration }}.</td>
                        <td><strong>{{ $meeting->title }}</strong></td>
                        <td>
                            {{ \Carbon\Carbon::parse($meeting->start_datetime)->locale('th')->translatedFormat('วันl ที่ j F Y เวลา H:i น.') }}
                            <br> ถึง
                            {{ \Carbon\Carbon::parse($meeting->end_datetime)->locale('th')->translatedFormat('วันl ที่ j F Y เวลา H:i น.') }}
                        </td>
                        <td>
                            @if ($meeting->meetingRoom)
                            {{ $meeting->meetingRoom->name }} (ความจุ: {{ $meeting->meetingRoom->capacity }}) (สถานที่:
                            {{ $meeting->meetingRoom->location }})
                            @else
                            ไม่ระบุห้องประชุม
                            @endif
                        </td>
                        <td>
                            {{$meeting->requester}}
                        </td>
                        <td class="text-center align-middle">
                            @if($meeting->type == 'open')
                            <span class="badge bg-info text-dark">ประชุมแบบเปิด</span>
                            @else
                            @if(in_array($meeting->id, $userRegistrations))
                            <form action="{{ route('registrations.cancel', $meeting->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" disabled>ลงทะเบียนแล้ว</button>
                            </form>
                            @elseif(in_array(auth()->id(), $meeting->participants ?? []))
                            <button class="btn btn-primary btn-sm btn-register"
                                data-url="{{ route('registrations.register', $meeting->id) }}"
                                data-meeting="{{ $meeting->title }}"
                                data-meeting-id="{{ $meeting->id }}">ลงทะเบียน</button>
                            @else
                            <button class="btn btn-secondary btn-sm" disabled>ไม่มีสิทธิ์ลงทะเบียน</button>
                            @endif
                            @endif
                        </td>
                        <td class="text-center align-middle">
                            @if($registration->leave_requested ?? false)
                            @if($registration->leave_approved ?? false)
                            <span class="badge bg-success">การลาได้รับการอนุมัติ</span>
                            @else
                            <span class="badge bg-warning">รอการอนุมัติ</span>
                            @endif
                            @else
                            <button class="btn btn-danger btn-sm btn-leave"
                                data-url="{{ route('registrations.requestLeave', $meeting->id) }}"
                                data-meeting="{{ $meeting->title }}"
                                data-meeting-id="{{ $meeting->id }}">ขอลาประชุม</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">ไม่พบข้อมูลการประชุม</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- ลิงก์หน้าเพจ -->
            <div class="d-flex justify-content-center">
                {{ $meetings->links() }}
            </div>
        </div>

        <!-- Modal Confirm -->
        <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-labelledby="confirmActionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmActionModalLabel">ยืนยันการดำเนินการ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        คุณต้องการ <span id="modalAction"></span> สำหรับการประชุม "<span id="modalMeetingTitle"></span>"
                        ใช่หรือไม่?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="button" class="btn btn-primary" id="confirmActionBtn">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            let actionUrl = '';
            let meetingId = '';

            document.querySelectorAll('.btn-register, .btn-leave').forEach(function(button) {
                button.addEventListener('click', function() {
                    actionUrl = this.getAttribute('data-url');
                    meetingId = this.getAttribute('data-meeting-id'); // เก็บ meeting_id
                    const meetingTitle = this.getAttribute('data-meeting');
                    const action = this.classList.contains('btn-register') ? 'ลงทะเบียน' :
                        'ขอลาประชุม';

                    document.getElementById('modalAction').innerText = action;
                    document.getElementById('modalMeetingTitle').innerText = meetingTitle;

                    // Show modal
                    new bootstrap.Modal(document.getElementById('confirmActionModal')).show();
                });
            });

            document.getElementById('confirmActionBtn').addEventListener('click', function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = actionUrl;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                // เพิ่ม hidden field สำหรับ meeting_id
                const meetingIdField = document.createElement('input');
                meetingIdField.type = 'hidden';
                meetingIdField.name = 'meeting_id';
                meetingIdField.value = meetingId;

                form.appendChild(csrfToken);
                form.appendChild(meetingIdField);
                document.body.appendChild(form);
                form.submit();
            });
        });
        </script>
        @endsection