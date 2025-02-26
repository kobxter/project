@extends('layouts.app')

@section('content')
<div class="container">
    <h1>แก้ไขข้อมูลส่วนตัว</h1>
    <form action="{{ route('user.updateProfile') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="email" class="form-label">อีเมล</label>
            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
            <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}">
        </div>
        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form>
</div>
@endsection
