@extends('layouts.app')

@section('content')
<div class="container">
    <h1>เพิ่มผู้ใช้</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">ชื่อ</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">อีเมล</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">รหัสผ่าน</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่าน</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">บทบาท</label>
            <select name="role" id="role" class="form-control" required>
                <option value="admin">ผู้ดูแลระบบ</option>
                <option value="user">ผู้ใช้งานทั่วไป</option>
                <option value="staff">พนักงาน</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
@endsection
