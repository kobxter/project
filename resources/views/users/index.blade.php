@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">จัดการผู้ใช้</h1>

    <a href="{{ route('users.create') }}" class="btn btn-success mb-4">
        <i class="fa fa-user-plus"></i> เพิ่มผู้ใช้
    </a>
    <a href="{{ route('meetingRooms.create') }}" class="btn btn-primary mb-4">
        <i class="fa fa-plus"></i> เพิ่มห้องประชุม
    </a>
    <a href="{{ route('meetingRooms.index') }}" class="btn btn-warning mb-4">
        <i class="fa fa-edit"></i> แก้ไขห้องประชุม
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
                        <th scope="col">ลำดับที่</th>
                        <th scope="col">ชื่อ</th>
                        <th scope="col">อีเมล</th>
                        <th scope="col">บทบาท</th>
                        <th scope="col">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></td>
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#viewUserModal-{{ $user->id }}">
                                <i class="fa fa-eye"></i> แสดงข้อมูล
                            </button>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i> แก้ไข
                            </a>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteUserModal-{{ $user->id }}">
                                <i class="fa fa-trash"></i> ลบ
                            </button>
                        </td>
                    </tr>
                    <!-- Modal สำหรับแสดงข้อมูลผู้ใช้ -->
                    <div class="modal fade" id="viewUserModal-{{ $user->id }}" tabindex="-1"
                        aria-labelledby="viewUserModalLabel-{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content shadow-lg border-0 rounded">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="viewUserModalLabel-{{ $user->id }}">ข้อมูลผู้ใช้</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>ชื่อ: </strong>{{ $user->name }}</p>
                                    <p><strong>ชื่อเล่น: </strong>{{ $user->nickname ?? '-' }}</p>
                                    <p><strong>อีเมล: </strong>{{ $user->email }}</p>
                                    <p><strong>บทบาท: </strong>{{ ucfirst($user->role) }}</p>
                                    <p><strong>แผนก: </strong>{{ $user->department ?? '-' }}</p>
                                    <p><strong>เบอร์โทรศัพท์: </strong>{{ $user->phone ?? '-' }}</p>
                                    <p><strong>ตำแหน่ง: </strong>{{ $user->position ?? '-' }}</p>
                                    <p><strong>วันที่สร้างบัญชี: </strong>{{ $user->created_at->format('d/m/Y H:i') }}
                                    </p>
                                    <p><strong>วันที่แก้ไขล่าสุด: </strong>{{ $user->updated_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Modal -->
                    <!-- Modal สำหรับยืนยันการลบ -->
                    <div class="modal fade" id="deleteUserModal-{{ $user->id }}" tabindex="-1"
                        aria-labelledby="deleteUserModalLabel-{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteUserModalLabel-{{ $user->id }}">ยืนยันการลบ</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้ <strong>{{ $user->name }}</strong> ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">ยกเลิก</button>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST">
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
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
<!-- Include Bootstrap Icons CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection