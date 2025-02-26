@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">แก้ไขการประชุม</h1>

    <!-- Modal for Alerts -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">การแจ้งเตือน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="alertModalMessage">
                    <!-- ข้อความแจ้งเตือน -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('meetings.update', $meeting->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-lg">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="start_datetime" class="form-label">วันที่ - เวลาเริ่มต้น</label>
                        <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime"
                            value="{{ old('start_datetime', $meeting->start_datetime ? $meeting->start_datetime->format('Y-m-d\TH:i') : '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="end_datetime" class="form-label">วันที่ - เวลาสิ้นสุด</label>
                        <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime"
                            value="{{ old('end_datetime', $meeting->end_datetime ? $meeting->end_datetime->format('Y-m-d\TH:i') : '') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="meeting_room_id" class="form-label">ห้องประชุม</label>
                    <select class="form-control select2" id="meeting_room_id" name="meeting_room_id" required>
                        <option value="">-- เลือกห้องประชุม --</option>
                        @foreach($meetingRooms as $room)
                        <option value="{{ $room->id }}" {{ old('meeting_room_id', $meeting->meeting_room_id) == $room->id ? 'selected' : '' }}>
                            {{ $room->name }} (ความจุ: {{ $room->capacity }}) (สถานที่: {{ $room->location }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">หัวข้อการประชุม</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ old('title', $meeting->title) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="requester" class="form-label">ผู้ขอจอง</label>
                        <input type="text" class="form-control" id="requester" name="requester"
                            value="{{ old('requester', $meeting->requester) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="department" class="form-label">ใช้สำหรับแผนก</label>
                        <input type="text" class="form-control" id="department" name="department"
                            value="{{ old('department', $meeting->department) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="chairperson" class="form-label">ประธานการประชุม</label>
                        <input type="text" class="form-control" id="chairperson" name="chairperson"
                            value="{{ old('chairperson', $meeting->chairperson) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label">ประเภทการประชุม</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="open" {{ old('type', $meeting->type) == 'open' ? 'selected' : '' }}>เปิด (ทุกคนสามารถลงทะเบียนได้)</option>
                            <option value="closed" {{ old('type', $meeting->type) == 'closed' ? 'selected' : '' }}>ปิด (เฉพาะผู้ที่มีรายชื่อเท่านั้น)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="participant_count" class="form-label">จำนวนผู้เข้าร่วมประชุม</label>
                        <input type="number" class="form-control" id="participant_count" name="participant_count"
                            value="{{ old('participant_count', $meeting->participant_count) }}" min="1" required>
                    </div>
                </div>

                <div class="mb-3" id="participants-container" style="{{ old('type', $meeting->type) == 'open' ? 'display:none;' : '' }}">
                    <label for="participants" class="form-label">ชื่อผู้เข้าร่วมประชุม</label>
                    <select class="form-control select2" id="participants" name="participants[]" multiple style="width: 100%;">
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ in_array($user->id, old('participants', $meeting->participants ?? [])) ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">อัปเดตการประชุม</button>
                <a href="{{ route('meetings.index') }}" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </div>
    </form>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#participants').select2({
        placeholder: "เลือกผู้เข้าร่วมประชุม"
    });
    $('#meeting_room_id').select2({
        placeholder: "เลือกห้องประชุม"
    });

    $('#type').change(function() {
        if ($(this).val() === 'open') {
            $('#participants-container').hide();
        } else {
            $('#participants-container').show();
        }
    });

    @if(session('conflict'))
        showAlertModal('{{ session('conflict') }}', 'warning');
    @elseif(session('success'))
        showAlertModal('{{ session('success') }}', 'success');
    @endif
});

function showAlertModal(message, type) {
    const alertModalMessage = document.getElementById('alertModalMessage');
    alertModalMessage.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    new bootstrap.Modal(document.getElementById('alertModal')).show();
}
</script>
<script>
$(document).ready(function() {
    // ตั้งค่าให้เลือกได้เฉพาะวันปัจจุบันขึ้นไป
    function setMinDateTime() {
        let now = new Date();
        let year = now.getFullYear();
        let month = (now.getMonth() + 1).toString().padStart(2, '0');
        let day = now.getDate().toString().padStart(2, '0');
        let hours = now.getHours().toString().padStart(2, '0');
        let minutes = now.getMinutes().toString().padStart(2, '0');

        let minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

        $('#start_datetime').attr('min', minDateTime);
        $('#end_datetime').attr('min', minDateTime);
    }

    setMinDateTime();

    // ตรวจสอบว่า End Date ต้องมากกว่า Start Date
    $('#end_datetime').change(function() {
        let start = new Date($('#start_datetime').val());
        let end = new Date($(this).val());

        if (end < start) {
            showAlertModal('วันที่-เวลาสิ้นสุดต้องมากกว่าหรือเท่ากับวันที่-เวลาเริ่มต้น', 'danger');
            $(this).val('');
        }
    });

    // ตรวจสอบห้องประชุมว่างหรือไม่
    function checkAvailability() {
        let startDatetime = $('#start_datetime').val();
        let endDatetime = $('#end_datetime').val();

        if (startDatetime && endDatetime) {
            $.post('{{ route('meetings.check-availability') }}', {
                _token: '{{ csrf_token() }}',
                start_datetime: startDatetime,
                end_datetime: endDatetime
            }, function(data) {
                $('#meeting_room_id').empty().append('<option value="">-- เลือกห้องประชุม --</option>');
                if (data.length > 0) {
                    $.each(data, function(index, room) {
                        $('#meeting_room_id').append(`<option value="${room.id}">${room.name} (ความจุ: ${room.capacity})</option>`);
                    });
                } else {
                    showAlertModal('ไม่พบห้องประชุมที่ว่างในช่วงเวลาที่เลือก', 'warning');
                    $('#meeting_room_id').val('');
                }
            }).fail(function() {
                showAlertModal('เกิดข้อผิดพลาดในการค้นหาห้องประชุม', 'danger');
            });
        }
    }

    $('#start_datetime, #end_datetime').change(function() {
        checkAvailability();
    });

    // แสดง Modal แจ้งเตือน
    function showAlertModal(message, type) {
        $('#alertModalMessage').html(`<div class="alert alert-${type}">${message}</div>`);
        new bootstrap.Modal(document.getElementById('alertModal')).show();
    }

    // แสดง Modal หากมี Error จาก Laravel Validation
    @if ($errors->any())
        let errorMessage = `{!! implode('<br>', $errors->all()) !!}`;
        showAlertModal(errorMessage, 'danger');
    @endif
});
</script>

@endsection
