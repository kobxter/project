@extends('layouts.app')

@section('content')
<style>
    .table thead th {
        background-color: #f39c12; /* สีส้ม */
        color: white; /* ตัวอักษรสีขาวเพื่อให้อ่านง่าย */
        text-align: center; /* จัดข้อความให้อยู่กึ่งกลาง */
    }
</style>

<div class="container">
    <h1>จัดการการลงทะเบียน</h1>
    <!-- Search form -->
    <form action="{{ route('registrations.manage') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="ค้นหาหัวข้อประชุม" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </div>
    </form>

    <!-- Alert Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">การแจ้งเตือน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="alertModalMessage">
                    <!-- ข้อความจะแสดงที่นี่ -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirm -->
    <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-labelledby="confirmActionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmActionModalLabel">ยืนยันการดำเนินการ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    คุณต้องการ <span id="modalAction"></span> สำหรับการลงทะเบียนนี้ใช่หรือไม่?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" id="confirmActionBtn">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>
        <!-- ปุ่มส่งออก PDF -->
        <a href="{{ route('reports.select') }}" class="btn btn-danger">
            <i class="fa fa-file-pdf"></i> ส่งออกรายงาน
        </a>
    <!-- Registration Table -->
    <div class="card shadow-lg">
        <div class="card-body ">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ชื่อผู้ใช้</th>
                        <th>ประชุม</th>
                        <th>วันที่ลงทะเบียน</th>
                        <th>สถานะการลา</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                        <tr>
                            <td>{{ $registration->user->first_name }} {{ $registration->user->last_name }}</td>
                            <td>{{ $registration->meeting->title }}</td>
                            <td>{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center align-middle">
                                @if($registration->leave_requested)
                                    @if($registration->leave_approved)
                                        <span class="badge bg-danger">ลา</span>
                                    @else
                                        <span class="badge bg-warning text-dark">รออนุมัติ</span>
                                    @endif
                                @else
                                    <span class="badge bg-success">ปกติ</span>
                                @endif
                            </td>

                            <td class="text-center align-middle">
                                @if($registration->leave_requested && !$registration->leave_approved)
                                    <button class="btn btn-success btn-sm btn-approve" 
                                            data-url="{{ route('registrations.approveLeave', $registration->id) }}" 
                                            data-action="อนุมัติการลา">อนุมัติการลา</button>
                                @endif
                                <button class="btn btn-danger btn-sm btn-cancel" 
                                        data-url="{{ route('registrations.cancelRegistration', $registration->id) }}" 
                                        data-action="ยกเลิกการลงทะเบียน">ยกเลิกการลงทะเบียน</button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">ไม่มีข้อมูลการลงทะเบียน</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>
    <!-- Chart Section -->
    <div class="card shadow-lg mb-4">
        <div class="card-body">
            <h5 class="card-title">สถิติการเข้าร่วมประชุม</h5>
            <canvas id="registrationChart"></canvas>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let actionUrl = '';
        let actionText = '';

        document.querySelectorAll('.btn-approve, .btn-cancel').forEach(function (button) {
            button.addEventListener('click', function () {
                actionUrl = this.getAttribute('data-url');
                actionText = this.getAttribute('data-action');

                document.getElementById('modalAction').innerText = actionText;
                
                // Show modal
                new bootstrap.Modal(document.getElementById('confirmActionModal')).show();
            });
        });

        document.getElementById('confirmActionBtn').addEventListener('click', function () {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            showAlertModal('{{ session('success') }}');
        @elseif($errors->any())
            showAlertModal('{{ $errors->first() }}');
        @endif
    });

    function showAlertModal(message) {
        document.getElementById('alertModalMessage').innerText = message;
        new bootstrap.Modal(document.getElementById('alertModal')).show();
    }
</script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('registrationChart').getContext('2d');

        var chartData = {
            labels: ['ปกติ', 'รออนุมัติลา', 'ลา'],
            datasets: [{
                label: 'จำนวนผู้เข้าร่วมประชุม',
                data: [{{ $normalCount }}, {{ $pendingLeaveCount }}, {{ $approvedLeaveCount }}],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        };

        new Chart(ctx, {
            type: 'bar',
            data: chartData,
        });
    });
</script>

@endsection
