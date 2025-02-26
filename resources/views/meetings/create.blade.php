@extends('layouts.app')

@section('content')
<div class="container">
    <h1>เพิ่มการประชุม</h1>

    <!-- Modal for Alerts -->
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

    <form id="meeting-form" action="{{ route('meetings.store') }}" method="POST">
        @csrf
        <div class="card shadow-lg">
            <div class="card-body ">
            <div class="row mb-3">
        <div class="col-md-6">
            <label for="start_datetime" class="form-label">วันที่ - เวลาเริ่มต้น</label>
            <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" required>
        </div>
        <div class="col-md-6">
            <label for="end_datetime" class="form-label">วันที่ - เวลาสิ้นสุด</label>
            <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" required>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function setMinDateTime() {
            let now = new Date();
            let year = now.getFullYear();
            let month = (now.getMonth() + 1).toString().padStart(2, '0');
            let day = now.getDate().toString().padStart(2, '0');
            let hours = now.getHours().toString().padStart(2, '0');
            let minutes = now.getMinutes().toString().padStart(2, '0');

            let minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

            document.getElementById('start_datetime').setAttribute('min', minDateTime);
            document.getElementById('end_datetime').setAttribute('min', minDateTime);
        }

        setMinDateTime();
    });
    </script>


                <div class="mb-3" id="meeting-room-container" style="display: none;">
                    <label for="meeting_room_id" class="form-label">ห้องประชุม</label>
                    <select class="form-control" id="meeting_room_id" name="meeting_room_id" required>
                        <option value="">-- เลือกห้องประชุม --</option>
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">หัวข้อการประชุม</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="col-md-6">
                        <label for="requester" class="form-label">ผู้ขอจอง</label>
                        <input type="text" class="form-control" id="requester" name="requester" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="department" class="form-label">ใช้สำหรับแผนก</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>
                    <div class="col-md-6">
                        <label for="chairperson" class="form-label">ประธานการประชุม</label>
                        <input type="text" class="form-control" id="chairperson" name="chairperson" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label">ประเภทการประชุม</label>
                        <select class="form-control" id="type" name="type" required>               
                            <option value="open">เปิด (ทุกคนสามารถลงทะเบียนได้)</option>
                            <option value="closed">ปิด (เฉพาะผู้ที่มีรายชื่อเท่านั้น)</option>             
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="participant_count" class="form-label">จำนวนผู้เข้าร่วมประชุม</label>
                        <input type="number" class="form-control" id="participant_count" name="participant_count" 
                            value="{{ old('participant_count', 1) }}" min="1" required>
                    </div>
                </div>
                <div class="mb-3" id="participants-container" style="display: none;">
                    <label for="participants" class="form-label">ชื่อผู้เข้าร่วมประชุม</label>
                    <input list="participants-list" class="form-control" id="participant-input">
                    <datalist id="participants-list">
                        @foreach($users as $user)
                        <option value="{{ $user->name }}" data-id="{{ $user->id }}"></option>
                        @endforeach
                    </datalist>
                    <div id="selected-participants" class="mt-2">
                        <!-- Selected participants will appear here -->
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">บันทึก</button>
                <a href="{{ route('meetings.index') }}" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const participantInput = document.getElementById('participant-input');
    const selectedParticipantsContainer = document.getElementById('selected-participants');
    const participantsList = document.getElementById('participants-list');

    participantInput.addEventListener('input', function() {
        const inputValue = participantInput.value;
        const option = Array.from(participantsList.options).find(opt => opt.value === inputValue);

        if (option) {
            const userId = option.dataset.id;
            if (!document.querySelector(`#participant-${userId}`)) {
                const participantBadge = document.createElement('span');
                participantBadge.className = 'badge bg-primary me-2';
                participantBadge.id = `participant-${userId}`;
                participantBadge.innerHTML = `${option.value} <button type="button" class="btn-close btn-close-white ms-2" aria-label="Remove"></button>`;
                
                selectedParticipantsContainer.appendChild(participantBadge);
                
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'participants[]';
                hiddenInput.value = userId;
                participantBadge.appendChild(hiddenInput);

                participantInput.value = ''; // Clear the input after selection

                participantBadge.querySelector('.btn-close').addEventListener('click', function() {
                    participantBadge.remove();
                });
            }
        }
    });

    document.getElementById('type').addEventListener('change', function() {
        const participantsContainer = document.getElementById('participants-container');
        if (this.value === 'open') {
            participantsContainer.style.display = 'none';
            selectedParticipantsContainer.innerHTML = ''; // Clear selected participants
        } else {
            participantsContainer.style.display = 'block';
        }
    });

    let startDatetimeFilled = false;
    let endDatetimeFilled = false;

    function checkAvailability() {
        const startDatetime = $('#start_datetime').val();
        const endDatetime = $('#end_datetime').val();

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
                    $('#meeting-room-container').show();
                } else {
                    showAlertModal('ไม่พบห้องประชุมที่ว่างในช่วงเวลาที่เลือก', 'warning');
                    $('#meeting-room-container').hide();
                }
            }).fail(function() {
                showAlertModal('เกิดข้อผิดพลาดในการค้นหาห้องประชุม', 'danger');
            });
        }
    }

    $('#start_datetime').change(function() {
        startDatetimeFilled = $(this).val() !== '';
        if (startDatetimeFilled && endDatetimeFilled) {
            checkAvailability();
        }
    });

    $('#end_datetime').change(function() {
        endDatetimeFilled = $(this).val() !== '';
        if (startDatetimeFilled && endDatetimeFilled) {
            checkAvailability();
        }
    });

    function showAlertModal(message, type) {
        const alertModalMessage = document.getElementById('alertModalMessage');
        alertModalMessage.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        new bootstrap.Modal(document.getElementById('alertModal')).show();
    }
});
</script>
@endsection
