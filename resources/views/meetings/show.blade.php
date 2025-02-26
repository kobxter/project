@extends('layouts.app')

@section('content')
<div class="container">
    <h1>รายละเอียดการประชุม</h1>
    <table class="table table-bordered">
        <tr>
            <th>หัวข้อ</th>
            <td>{{ $meeting->title }}</td>
        </tr>
        <tr>
            <th>วันที่ - เวลา</th>
            <td>
                {{ \Carbon\Carbon::parse($meeting->start_datetime)->locale('th')->translatedFormat('วันl ที่ j F Y เวลา H:i น.') }}
                <br> ถึง
                {{ \Carbon\Carbon::parse($meeting->end_datetime)->locale('th')->translatedFormat('เวลา H:i น.') }}
            </td>
        </tr>
        <tr>
            <th>ห้องประชุม</th>
            <td>
                {{ $meeting->meetingRoom->name ?? 'ไม่ระบุ' }}
                (ความจุ: {{ $meeting->meetingRoom->capacity ?? 'ไม่ระบุ' }})
                (สถานที่: {{ $meeting->meetingRoom->location ?? 'ไม่ระบุ' }})
            </td>
        </tr>
        <tr>
            <th>ผู้เข้าร่วมประชุม</th>
            <td>
                <ul>
                    @foreach ($meeting->participants as $participantId)
                        @php
                            $user = \App\Models\User::find($participantId);
                        @endphp
                        <li>{{ $user->name ?? 'ไม่ระบุ' }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
    </table>
    <a href="{{ route('roomSchedule') }}" class="btn btn-secondary">กลับ</a>
</div>
@endsection
