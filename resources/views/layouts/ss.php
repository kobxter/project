<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการประชุม</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="shortcut icon" type="x-icon" href="{{ asset('images/it_department_logo.png') }}">
    <!-- Bootstrap, jQuery, and Popper.js for dropdown functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <style>
        .table-container, .selected-courses-container, .summary-container {
            margin: 20px 0;
        }
        footer {
            margin-top: 20px;
            text-align: center;
        }
        /* ตรึง navbar ให้อยู่ด้านบนตลอด */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 999;
        }
        /* ปรับให้เนื้อหาเลื่อนลงมาหลังจากมี navbar คงที่ */
        body {
            padding-top: 70px; /* ปรับระยะห่างให้พอดีกับความสูงของ navbar */
        }
        h1, {
            color: #007bff; /* สีหัวข้อ */
        }
        h2, {
            color: #FFCC00; /* สีหัวข้อ */
        }
        /* ใช้ฟอนต์ Noto Sans Thai กับทั้งเว็บไซต์ */
        body {
            font-family: "Noto Sans Thai", serif;
            font-weight: 400; /* น้ำหนักตัวอักษรปกติ */
            font-style: normal;
            font-variation-settings: "wdth" 100;
        }
        /* ตัวอย่างปรับเฉพาะส่วน */
        .noto-sans-thai-bold {
            font-family: "Noto Sans Thai", serif;
            font-weight: 700; /* น้ำหนักตัวอักษรหนา */
        }
        .noto-sans-thai-light {
            font-family: "Noto Sans Thai", serif;
            font-weight: 300; /* น้ำหนักตัวอักษรบาง */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-gradient-green">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- โลโก้ -->
        <div class="d-flex align-items-center">
            <a href="{{ url('/') }}" title="หน้าหลัก">
                <img src="{{ asset('img/logohos.png') }}" alt="Logo Hospital" title="หน้าหลัก" style="height: 50px; margin-right: 5px;">
            </a>
            <button class="btn btn-outline-light btn-sm mx-1" onclick="window.history.back()" title="ย้อนกลับ">
                <i class="fas fa-arrow-left"></i> ←
            </button>
            <button class="btn btn-outline-light btn-sm mx-1" onclick="window.history.forward()" title="ไปข้างหน้า">
                →  <i class="fas fa-arrow-right"></i>
            </button>
        </div>       
        <!-- ฟังก์ชันใน Navbar -->
        <div class="d-flex justify-content-center ml-auto">
            @if (Route::has('login'))
                <div class="d-flex">
                    @auth
                        @php
                            $user = auth()->user();
                        @endphp
                        @if ($user->role === 'admin' || $user->role === 'staff')
                            <a class="navbar-brand" href="{{ url('/staffwork') }}" title="งานสำหรับพนักงานเจ้าหน้าที่">ระบบจัดการประชุม</a>
                        @endif
                        <a class="navbar-brand" href="{{ url('/') }}" title="หน้าหลัก">หน้าหลัก</a>
                        <a class="navbar-brand" href="{{ url('/profile') }}" title="ข้อมูลส่วนตัวผู้ใช้งาน">ข้อมูลส่วนตัว</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;" onsubmit="clearSelectedCourses()">
                            @csrf
                            <button type="submit" class="btn logout-btn">ออกจากระบบ</button>
                        </form>
                    @else
                        <a class="navbar-brand" href="{{ route('login') }}">เข้าสู่ระบบ</a>
                        @if (Route::has('register'))
                            <a class="navbar-brand" href="{{ route('register') }}">สมัครสมาชิก</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>
</nav>
<style>
    /* ไล่เฉดสีเขียวสำหรับ navbar */
    .bg-gradient-green {
        background: linear-gradient(90deg, #28a745, #218838, #1e7e34); /* ไล่เฉดสีเขียว */
    }
    /* ปุ่ม Backward และ Forward */
    .btn-outline-light {
        color: #ffffff;      
    }
    .btn-outline-light:hover {
        background-color: #ffffff;
        color: #000000;
    }
    /* ปุ่มออกจากระบบให้มีสไตล์สอดคล้องกับ navbar */
    .logout-btn {
        border: none;
        color: #ffffff;
        padding: 8px 16px;
        font-size: 1rem;
        border-radius: 5px;
        transition: background 0.3s ease-in-out;
    }
</style>
    <main>
        @yield('content')
    </main>
</body>
</html>