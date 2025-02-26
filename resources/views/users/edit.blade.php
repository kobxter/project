@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">แก้ไขข้อมูลผู้ใช้</h1>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="card mt-4">
                    <div class="card-header">{{ __('แก้ไขข้อมูลผู้ใช้') }}</div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="department" class="col-md-4 col-form-label text-md-end">แผนก</label>
                            <div class="col-md-6">
                                <input id="department" type="text" class="form-control" name="department"
                                    value="{{ old('department', $user->department) }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="title" class="col-md-4 col-form-label text-md-end">คำนำหน้าชื่อ</label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title"
                                    value="{{ old('title', $user->title) }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">ชื่อจริง</label>
                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control" name="first_name"
                                    value="{{ old('first_name', $user->first_name) }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="last_name" class="col-md-4 col-form-label text-md-end">นามสกุล</label>
                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control" name="last_name"
                                    value="{{ old('last_name', $user->last_name) }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nickname" class="col-md-4 col-form-label text-md-end">ชื่อเล่น</label>
                            <div class="col-md-6">
                                <input id="nickname" type="text" class="form-control" name="nickname"
                                    value="{{ old('nickname', $user->nickname) }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">อีเมล</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">รหัสผ่าน
                                (เว้นว่างหากไม่เปลี่ยน)</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password_confirmation"
                                class="col-md-4 col-form-label text-md-end">ยืนยันรหัสผ่าน</label>
                            <div class="col-md-6">
                                <input id="password_confirmation" type="password" class="form-control"
                                    name="password_confirmation">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">เบอร์โทรศัพท์</label>
                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone"
                                    value="{{ old('phone', $user->phone) }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="position" class="col-md-4 col-form-label text-md-end">ตำแหน่ง</label>
                            <div class="col-md-6">
                                <input id="position" type="text" class="form-control" name="position"
                                    value="{{ old('position', $user->position) }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">บทบาท</label>
                            <div class="col-md-6">
                                <select id="role" class="form-control" name="role" required>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                        ผู้ดูแลระบบ</option>
                                    <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>
                                        พนักงาน</option>
                                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                        ผู้ใช้งานทั่วไป</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">ยกเลิก</a>
                            </div>
                        </div>
    </form>
</div>
@endsection