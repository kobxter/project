@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
@endif
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="mb-0">ข้อมูลส่วนตัว</h3>
                </div>
                <div class="card-body p-4">
                @if(auth()->check())
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">คำนำหน้าชื่อ</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{ auth()->user()->title }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">ชื่อจริง</label>
                                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ auth()->user()->first_name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">นามสกุล</label>
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ auth()->user()->last_name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="nickname" class="form-label">ชื่อเล่น</label>
                                <input id="nickname" type="text" class="form-control" name="nickname" value="{{ auth()->user()->nickname }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">อีเมล</label>
                                <input id="email" type="text" class="form-control" name="email" value="{{ auth()->user()->email }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="role" class="form-label">ประเภทผู้ใช้</label>
                                <input id="role" type="text" class="form-control" name="role" value="{{ auth()->user()->role }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="position" class="form-label">ตำแหน่ง</label>
                                <input id="position" type="text" class="form-control" name="position" value="{{ auth()->user()->position }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="department" class="form-label">แผนก</label>
                                <input id="department" type="text" class="form-control" name="department" value="{{ auth()->user()->department }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">สถานะ</label>
                                <input id="status" type="text" class="form-control" name="status" value="{{ auth()->user()->status }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                @else
                    <p class="text-danger">คุณยังไม่ได้เข้าสู่ระบบ กรุณาเข้าสู่ระบบเพื่อดูข้อมูลส่วนตัว</p>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
