@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center">เลือกข้อมูลเพื่อดูรายงาน</h2>
    <div class="card shadow-lg">
        <div class="card-body">
            <form action="{{ route('reports.export') }}" method="GET" target="_blank">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="user_id" class="form-label">เลือกผู้ใช้</label>
                        <select class="form-control select2" name="user_id" id="user_id">
                            <option value="">-- ทั้งหมด --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="meeting_id" class="form-label">เลือกประชุม</label>
                        <select class="form-control select2" name="meeting_id" id="meeting_id">
                            <option value="">-- ทั้งหมด --</option>
                            @foreach($meetings as $meeting)
                                <option value="{{ $meeting->id }}">{{ $meeting->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label">วันที่เริ่มต้น</label>
                        <input type="date" class="form-control" name="start_date" id="start_date">
                    </div>
                    <div class="col-md-6">
                        <label for="end_date" class="form-label">วันที่สิ้นสุด</label>
                        <input type="date" class="form-control" name="end_date" id="end_date">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="leave_status" class="form-label">สถานะการลา</label>
                    <select class="form-control" name="leave_status" id="leave_status">
                        <option value="">-- ทั้งหมด --</option>
                        <option value="normal">ปกติ</option>
                        <option value="pending">รออนุมัติ</option>
                        <option value="approved">ได้รับอนุมัติ</option>
                    </select>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    <button type="submit" name="action" value="view" class="btn btn-primary me-2">
                        <i class="fa fa-eye"></i> ดูรายงาน
                    </button>
                    <!-- <button type="submit" name="action" value="export" class="btn btn-success">
                        <i class="fa fa-file-pdf"></i> ส่งออก PDF(bug)
                    </button> -->
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Select2 Script -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "เลือก",
        allowClear: true
    });
});
</script>

@endsection
