@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">จัดการห้องประชุม</h1>

    <a href="{{ route('meetingRooms.create') }}" class="btn btn-primary mb-4">
        <i class="fa fa-plus"></i> เพิ่มห้องประชุม
    </a>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-lg">
        <div class="card-body">
            <table class="table table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ลำดับที่</th>
                        <th>ชื่อห้องประชุม</th>
                        <th>ความจุ</th>
                        <th>ที่ตั้ง</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($meetingRooms as $room)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->capacity }}</td>
                        <td>{{ $room->location ?? '-' }}</td>
                        <td>
                            <a href="{{ route('meetingRooms.edit', $room) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i> แก้ไข
                            </a>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteMeetingRoomModal-{{ $room->id }}">
                                <i class="fa fa-trash"></i> ลบ
                            </button>
                        </td>
                    </tr>
                    <!-- Modal สำหรับยืนยันการลบ -->
                    <div class="modal fade" id="deleteMeetingRoomModal-{{ $room->id }}" tabindex="-1"
                        aria-labelledby="deleteMeetingRoomModalLabel-{{ $room->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteMeetingRoomModalLabel-{{ $room->id }}">ยืนยันการลบ</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    คุณแน่ใจหรือไม่ว่าต้องการลบห้องประชุม <strong>{{ $room->name }}</strong> ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                    <form action="{{ route('meetingRooms.destroy', $room) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">ลบ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Modal -->
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $meetingRooms->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
