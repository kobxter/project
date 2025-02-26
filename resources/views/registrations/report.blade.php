<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานผู้เข้าร่วมประชุม</title>

    <!-- ตั้งค่าฟอนต์ไทย -->
    <style>
        @font-face {
            font-family: 'THSarabunPSK';
            src: url("{{ storage_path('fonts/THSarabunPSK.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'THSarabunPSK';
            src: url("{{ storage_path('fonts/THSarabunPSK-Bold.ttf') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        body {
            font-family: 'THSarabunPSK', sans-serif;
            font-size: 14px;
            margin: 10px;
            line-height: 1.4;
        }

        .container {
            max-width: 100%;
            margin: auto;
            text-align: center;
        }

        .report-header {
            text-align: left;
            font-size: 16px;
            margin-bottom: 5px;
            border-bottom: 2px solid black;
            padding-bottom: 5px;
        }

        .filter-info {
            text-align: left;
            font-size: 14px;
            margin-bottom: 10px;
            font-style: italic;
        }

        .print-btn {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            border-radius: 3px;
            margin-bottom: 10px;
        }

        .print-btn:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            word-wrap: break-word;
        }

        /* ทำให้คอลัมน์ ชื่อผู้ใช้ และ ประชุม ชิดซ้าย */
        td:nth-child(2), td:nth-child(3) {
            text-align: left; /* ชิดซ้าย */
        }

        th {
            background-color: #f39c12;
            color: black;  /* เปลี่ยนเป็นสีดำ */
            font-weight: bold;  /* ตัวหนา */
            font-size: 14px;
        }

        /* แยกหน้า */
        @media print {
            .print-btn {
                display: none;
            }

            body {
                font-size: 12px;
            }

            table {
                page-break-inside: auto;
            }

            .page-break {
                page-break-after: always;
            }

            .filter-info {
                font-size: 12px;
                font-style: italic;
                color: #555;
            }

            /* ทำให้กราฟอยู่หน้าแรก */
            #chartContainer {
                display: block !important;
                width: 100%;
                height: auto;
                margin: auto;
            }

            canvas {
                max-width: 100% !important;
                height: auto !important;
            }
        }

        /* กราฟแสดงที่หน้าแรก */
        #chartContainer {
            width: 80%; /* ปรับความกว้างของกราฟ */
            max-width: 500px; /* จำกัดความกว้างสูงสุด */
            margin: auto;
            padding-top: 10px;
            display: block;
        }

        canvas {
            max-width: 100% !important;
            height: auto !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

</head>
<body>

    <div class="container">
        <h2>รายงานผู้เข้าร่วมประชุม</h2>

        <button class="print-btn" onclick="window.print()">
            <i class="fa fa-print"></i> พิมพ์รายงาน
        </button>

        <!-- รายละเอียดของตัวกรอง -->
        <div class="report-header">
            <strong>จัดทำโดย:</strong> ระบบลงทะเบียนประชุมออนไลน์<br>
            <strong>วันที่ออกเอกสาร:</strong> {{ now()->format('d/m/Y H:i') }}
        </div>

        <!-- กราฟวิเคราะห์ข้อมูล (อยู่หน้าแรก) -->
        <div id="chartContainer">
            <canvas id="attendanceChart"></canvas>
        </div>

        <!-- ตัดหน้าก่อนเริ่มตาราง -->
        <div class="page-break"></div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">ลำดับ</th>
                    <th style="width: 20%;">ชื่อผู้ใช้</th>
                    <th style="width: 35%;">ประชุม</th>
                    <th style="width: 20%;">วันที่ลงทะเบียน</th>
                    <th style="width: 10%;">สถานะลา</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $registration)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $registration->user->first_name }} {{ $registration->user->last_name }}</td>
                        <td>{{ $registration->meeting->title }}</td>
                        <td>{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($registration->leave_requested)
                                {{ $registration->leave_approved ? 'ลา' : 'รออนุมัติ' }}
                            @else
                                ปกติ
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById("attendanceChart").getContext("2d");

        const data = {
            labels: ["เข้าร่วมปกติ", "รออนุมัติลา", "ได้รับอนุมัติลา"],
            datasets: [{
                label: "สถิติการเข้าร่วมประชุม",
                data: [{{ $normalCount }}, {{ $pendingLeaveCount }}, {{ $approvedLeaveCount }}],
                backgroundColor: ["#28a745", "#ffc107", "#dc3545"],
                borderWidth: 1
            }]
        };

        // คำนวณค่ารวมของข้อมูลทั้งหมดใน datasets
        const total = data.datasets[0].data.reduce((a, b) => a + b, 0);

        const config = {
            type: "pie",
            data: data,
            plugins: [ChartDataLabels], // ลงทะเบียน plugin
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "bottom"
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            let percentage = value.toFixed(0);
                            return percentage + " คน";
                        },
                        color: '#fff', // กำหนดสีตัวหนังสือใน label
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            }
        };

        new Chart(ctx, config);
    });
</script>

</body>
</html>
