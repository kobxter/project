@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ค้นหาห้องประชุมว่าง</h1>

    <form action="{{ route('meetings.selectRoom') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="start_datetime" class="form-label">วันที่ - เวลาเริ่มต้น</label>
            <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" required>
        </div>
        <div class="mb-3">
            <label for="end_datetime" class="form-label">วันที่ - เวลาสิ้นสุด</label>
            <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" required>
        </div>
        <button type="submit" class="btn btn-primary">ค้นหาห้องว่าง</button>
    </form>

    @if(isset($availableRooms))
    <h2 class="mt-4">ห้องประชุมว่าง</h2>
    @foreach($availableRooms as $room)
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $room->name }}</h5>
            <p class="card-text">ความจุ: {{ $room->capacity }}</p>
            <p class="card-text">สถานที่: {{ $room->location }}</p>
            <a href="{{ route('meetings.createWithRoom', ['roomId' => $room->id, 'start_datetime' => $validatedData['start_datetime'], 'end_datetime' => $validatedData['end_datetime']]) }}" class="btn btn-success">เลือกห้องนี้</a>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection
