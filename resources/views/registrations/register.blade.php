@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ลงทะเบียนการประชุม: {{ $meeting->title }}</h1>
    <form action="{{ route('registrations.store') }}" method="POST">
        @csrf
        <input type="hidden" name="meeting_id" value="{{ $meeting->id }}">
        <button type="submit" class="btn btn-success">ยืนยันการลงทะเบียน</button>
    </form>
</div>
@endsection
