@extends('layouts.app')
@section('content')
<style>
.table thead th {
    background-color: rgb(253, 188, 8);
    /* สีส้ม */
    color: white;
    /* ตัวอักษรสีขาวเพื่อให้อ่านง่าย */
    text-align: center;
    /* จัดข้อความให้อยู่กึ่งกลาง */
}

#create-meeting-form {
    display: none;
    margin-top: 20px;
    opacity: 0;
    /* เริ่มต้นด้วยการตั้งค่า opacity เป็น 0 */
    transform: scale(0.95);
    /* ลดขนาดเล็กลงเล็กน้อย */
    transition: opacity 0.3s ease, transform 0.3s ease;
    /* การเปลี่ยนแปลงที่นุ่มนวล */
}

#create-meeting-form.show {
    display: block;
    opacity: 1;
    /* ฟอร์มจะปรากฏ */
    transform: scale(1);
    /* ขยายกลับมาขนาดปกติ */
}

/* ...existing code... */
#toggle-create-meeting {
    transition: transform 0.3s ease-in-out;
}

#toggle-create-meeting:hover {
    transform: scale(1.1);
}

.modal-content {
    border-radius: 10px;
    overflow: hidden;
    font-size: 0.95rem;
    /* ขนาดตัวอักษรพอเหมาะ */
}

.modal-header {
    padding: 1rem 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-body h6 {
    font-weight: bold;
    margin-bottom: 0.3rem;
}

.modal-body p,
.modal-body ul {
    margin-bottom: 1rem;
}

.list-group-item {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    border: none;
}
</style>

<div class="container">
    <h1 class="text-center align-center">ระบบจัดการประชุม</h1>
    <!-- ฟอร์มสำหรับกรอกข้อมูลการประชุม -->
    <style>
    .btn-toggle-animate {
        transition: transform 0.3s ease, background-color 0.3s ease;
    }

    .btn-toggle-animate:active {
        transform: scale(0.95);
        /* ขนาดเล็กลงเมื่อคลิก */
    }

    .btn-toggle-animate:hover {
        background-color: #d68c0a;
        /* สีเข้มขึ้นเมื่อ hover */
        transform: scale(1.1);
    }
    </style>
    <a id="toggle-create-meeting" href="{{ route('registrations.manage') }}" class="btn btn-warning btn-toggle-animate">
        <i class="fa fa-eye"></i> จัดการการลงทะเบียนประชุม
    </a>
    <hr>
    <a id="toggle-create-meeting" href="{{ route('meetings.create') }}" class="btn btn-success mb-3"> 
        <i class="fa fa-plus"></i> เพิ่มการประชุม
    </a> 
    <script>
    document.querySelector('.btn-toggle-animate').addEventListener('click', function() {
        this.classList.add('animate-button');
        setTimeout(() => {
            this.classList.remove('animate-button');
        }, 300); // ระยะเวลา animation
    });
    </script>
    <!-- แทนที่ button ด้วย anchor tag -->

    <!-- แสดงข้อมูลการประชุม -->
    <h2>รายการการประชุม</h2>
    <!-- ฟอร์มค้นหา -->
    <form action="{{ route('meetings.index') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="ค้นหาหัวข้อการประชุม"
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </div>
    </form>
    <div class="card shadow-lg">
        <div class="card-body ">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center align-middle" title="แสดงลำดับที่ของการประชุม">ลำดับที่</th>
                        <th class="text-center align-middle" title="หัวข้อของการประชุม">หัวข้อการประชุม</th>
                        <th class="text-center align-middle" title="รายชื่อผู้เข้าร่วมประชุม">ผู้ขอจอง</th>
                        <th class="text-center align-middle" title="จำนวนผู้เข้าร่วม">จำนวนผู้เข้าร่วม</th>
                        <th class="text-center align-middle" title="ช่วงวันและเวลาในการประชุม">วันที่ - เวลา</th>
                        <th class="text-center align-middle" title="ห้องประชุม">ห้องประชุม</th>
                        <th class="text-center align-middle" title="สถานะการประชุม">สถานะ</th>
                        <th class="text-center align-middle" title="ตัวเลือกสำหรับแก้ไขหรือยกเลิกการประชุม">การจัดการ
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $meeting)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}.</td>
                        <td><strong>{{ $meeting->title }}</strong></td>
                        <td>
                            {{$meeting->requester}}
                        </td>
                        <td class="text-center align-middle">{{$meeting->participant_count}}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($meeting->start_datetime)->locale('th')->translatedFormat('j M y') }}
                            ({{ \Carbon\Carbon::parse($meeting->start_datetime)->format('H:i') }})
                            -
                            {{ \Carbon\Carbon::parse($meeting->end_datetime)->locale('th')->translatedFormat('j M y') }}
                            ({{ \Carbon\Carbon::parse($meeting->end_datetime)->format('H:i') }})
                        </td>
                        <td>
                            @if ($meeting->meetingRoom)
                            {{ $meeting->meetingRoom->name }}
                            (ความจุ: {{ $meeting->meetingRoom->capacity }})
                            (สถานที่: {{ $meeting->meetingRoom->location }})
                            @else
                            ไม่ระบุห้องประชุม
                            @endif
                        </td>
                        <td class="text-center align-middle">
                            @php
                            $now = \Carbon\Carbon::now();
                            $isMeetingFinished =
                            $now->greaterThanOrEqualTo(\Carbon\Carbon::parse($meeting->end_datetime));
                            @endphp
                            <span class="badge {{ $isMeetingFinished ? 'bg-success' : 'bg-warning' }}">
                                {{ $isMeetingFinished ? 'เสร็จสิ้น' : 'กำลังดำเนินการ' }}
                            </span>
                        </td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#meetingDetailsModal-{{ $meeting->id }}">
                                แสดงข้อมูลการประชุม
                            </button>
                            @if(!$isMeetingFinished)
                            <a href="{{ route('meetings.edit', $meeting->id) }}"
                                class="btn btn-warning btn-sm">แก้ไข</a>
                            <button type="button" class="btn btn-danger btn-sm btn-delete"
                                data-url="{{ route('meetings.destroy', $meeting->id) }}">ยกเลิก</button>
                            @endif
                        </td>
                        <!-- Modal Confirm Delete -->
                        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
                            aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">ยืนยันการยกเลิกการประชุม
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        คุณต้องการยกเลิกการประชุมนี้ใช่หรือไม่?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">ยกเลิก</button>
                                        <button type="button" class="btn btn-danger"
                                            id="confirmDeleteBtn">ยืนยัน</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal สำหรับแสดงข้อมูลการประชุม -->
                        <div class="modal fade" id="meetingDetailsModal-{{ $meeting->id }}" tabindex="-1"
                            aria-labelledby="meetingDetailsModalLabel-{{ $meeting->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content shadow-lg border-0 rounded">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="meetingDetailsModalLabel-{{ $meeting->id }}">
                                            <i class="fas fa-calendar-alt"></i> ข้อมูลการประชุม: {{ $meeting->title }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">หัวข้อการประชุม</h6>
                                                    <p class="mb-0">{{ $meeting->title }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">ผู้ขอจอง</h6>
                                                    <p class="mb-0">{{ $meeting->requester }}</p>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">ใช้สำหรับแผนก</h6>
                                                    <p class="mb-0">{{ $meeting->department }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">ประธานการประชุม</h6>
                                                    <p class="mb-0">{{ $meeting->chairperson }}</p>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">ประเภทการประชุม</h6>
                                                    <p class="mb-0">{{ $meeting->type === 'open' ? 'เปิด' : 'ปิด' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">วันเวลาเริ่มต้น</h6>
                                                    <p class="mb-0">
                                                        {{ \Carbon\Carbon::parse($meeting->start_datetime)->locale('th')->translatedFormat('วันl ที่ j F Y เวลา H:i น.') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">วันเวลาสิ้นสุด</h6>
                                                    <p class="mb-0">
                                                        {{ \Carbon\Carbon::parse($meeting->end_datetime)->locale('th')->translatedFormat('วันl ที่ j F Y เวลา H:i น.') }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">ห้องประชุม</h6>
                                                    <p class="mb-0">
                                                        @if ($meeting->meetingRoom)
                                                        {{ $meeting->meetingRoom->name }}
                                                        (ความจุ: {{ $meeting->meetingRoom->capacity }})
                                                        (สถานที่: {{ $meeting->meetingRoom->location }})
                                                        @else
                                                        ไม่ระบุห้องประชุม
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">จำนวนผู้เข้าร่วมประชุม</h6>
                                                    <p class="mb-0">{{ $meeting->participant_count }} คน</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="text-muted">ผู้เข้าร่วมประชุม</h6>
                                                    <ul class="list-group">
                                                        @foreach($meeting->participants as $participantId)
                                                        @php
                                                        $user = $users->firstWhere('id', $participantId);
                                                        @endphp
                                                        @if($user)
                                                        <li class="list-group-item">{{ $user->name }}</li>
                                                        @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
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

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">ไม่พบข้อมูลการประชุมที่ตรงกับคำค้นหา</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $meetings->links() }}
        </div>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

        <script>
        $(document).ready(function() {
            $('#participants').select2({
                placeholder: "เลือกผู้เข้าร่วมประชุม"
            });
            $('#location').select2({
                placeholder: "เลือกสถานที่ประชุม"
            });
        });
        </script>

        <script>
        document.getElementById('add-participant').addEventListener('click', function() {
            const participantFields = document.getElementById('participant-fields');
            const newField = document.createElement('div');
            newField.className = 'd-flex mb-2 align-items-center participant-field';
            newField.innerHTML = `
        <input type="text" class="form-control me-2" name="participants[]" required>
        <button type="button" class="btn btn-danger btn-sm remove-participant">ยกเลิก</button>
    `;
            participantFields.appendChild(newField);
            // ทำให้ปุ่ม "ยกเลิก" ใช้งานได้
            newField.querySelector('.remove-participant').addEventListener('click', function() {
                newField.remove();
            });
        });
        // เพิ่ม event listener ให้ปุ่มยกเลิกที่สร้างใหม่
        document.querySelectorAll('.remove-participant').forEach(function(button) {
            button.addEventListener('click', function() {
                button.closest('.participant-field').remove();
            });
        });
        </script>
        <script>
        document.getElementById('toggle-create-meeting').addEventListener('click', function() {
            const form = document.getElementById('create-meeting-form');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block'; // แสดงฟอร์ม
            } else {
                form.style.display = 'none'; // ซ่อนฟอร์ม
            }
        });
        </script>
        <script>
        $(document).ready(function() {
            // Initialize Select2 plugin with proper width for the participants dropdown
            $('#participants').select2({
                placeholder: "เลือกผู้เข้าร่วมประชุม", // Set placeholder in Thai
                allowClear: true,
                width: '100%' // Ensure full width
            });
        });
        </script>
        <script>
        document.getElementById('toggle-create-meeting').addEventListener('click', function() {
            this.classList.add('animate-button');
            setTimeout(() => {
                this.classList.remove('animate-button');
            }, 300);
        });
        </script>
        <script>
        document.getElementById('toggle-create-meeting').addEventListener('click', function() {
            const form = document.getElementById('create-meeting-form');

            if (form.classList.contains('show')) {
                // ซ่อนฟอร์ม
                form.classList.remove('show');
                setTimeout(() => {
                    form.style.display = 'none'; // ซ่อนด้วย display หลัง animation จบ
                }, 300); // รอเวลา animation จบ
            } else {
                // แสดงฟอร์ม
                form.style.display = 'block'; // แสดงก่อนเพื่อเริ่ม animation
                setTimeout(() => form.classList.add('show'), 10); // ใช้ setTimeout เพื่อเริ่ม animation
            }
        });
        </script>

        <!-- Alert Modal -->
        <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="alertModalLabel">การแจ้งเตือน</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="alertModalMessage">
                        <!-- ข้อความจะแสดงที่นี่ -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('conflict'))
            showAlertModal(`{{ session('conflict') }}`, 'warning');
            @endif
        });

        function showAlertModal(message, type) {
            const alertModalMessage = document.getElementById('alertModalMessage');
            alertModalMessage.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            new bootstrap.Modal(document.getElementById('alertModal')).show();
        }
        </script>
        <script>
        document.querySelectorAll('.btn-delete').forEach(function(deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                var deleteUrl = this.getAttribute('data-url');
                var confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                confirmModal.show();

                document.getElementById('confirmDeleteBtn').onclick = function() {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;

                    var csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    var methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                };
            });
        });
        </script>

        @endsection