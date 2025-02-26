@extends('layouts.app')

@section('content')
<div class="container">
    <h1>แก้ไขห้องประชุม</h1>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('meetingRooms.update', $meetingRoom) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">ชื่อห้องประชุม</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $meetingRoom->name }}" required>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">ความจุ</label>
            <input type="number" class="form-control" id="capacity" name="capacity" value="{{ $meetingRoom->capacity }}" min="1" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">ที่ตั้ง</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ $meetingRoom->location }}">
        </div>
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <a href="{{ route('meetingRooms.index') }}" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
@endsection
