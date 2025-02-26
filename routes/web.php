<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MeetingRoomController;
use App\Http\Controllers\ReportController;



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('adminHome');
});

Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/staff', [StaffController::class, 'index'])->name('staffHome');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('userHome');
});

Route::get('/staffwork', function () {
    return view('staffwork');
});

Route::resource('meetings', MeetingController::class);
Route::get('/staffwork', [MeetingController::class, 'index'])->name('meetings.index');
Route::get('meetings/{id}/edit', [MeetingController::class, 'edit'])->name('meetings.edit');
Route::get('/', [MeetingController::class, 'roomSchedule'])->name('roomSchedule');
Route::get('/meetings/{id}', [MeetingController::class, 'show'])->name('meetings.show');
Route::get('/api/events', [MeetingController::class, 'getEvents']);
Route::get('/api/events', [MeetingController::class, 'getEventsByDate']);
Route::get('/api/events', [MeetingController::class, 'getAllEvents']);


Auth::routes();
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile');
Route::get('/profile', function () {
    return view('profile');
})->name('profile');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

Route::middleware('auth')->group(function () {
    Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/registrations/{meetingId}/register', [RegistrationController::class, 'register'])->name('registrations.register');
    Route::post('/registrations', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/registrations/manage', [RegistrationController::class, 'manage'])->name('registrations.manage');
    Route::put('/user/profile', [RegistrationController::class, 'updateProfile'])->name('user.updateProfile');
    Route::delete('/registrations/{meeting}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');
    Route::post('/registrations/{meetingId}/register', [RegistrationController::class, 'store'])->name('registrations.store');
});
Route::get('/events', [EventController::class, 'index']);
Route::post('/events', [EventController::class, 'store']);
Route::delete('/events/{id}', [EventController::class, 'destroy']);
Route::put('/events/{id}', [EventController::class, 'update']);

Route::post('/registrations/request-leave/{meeting}', [RegistrationController::class, 'requestLeave'])->name('registrations.requestLeave');
Route::post('/registrations/approve-leave/{id}', [RegistrationController::class, 'approveLeave'])->name('registrations.approveLeave');
Route::post('/registrations/cancel/{id}', [RegistrationController::class, 'cancelRegistration'])->name('registrations.cancelRegistration');

Route::get('/meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');
Route::post('/meetings/store', [MeetingController::class, 'store'])->name('meetings.store');
Route::post('/meetings/check-availability', [MeetingController::class, 'checkAvailability'])->name('meetings.check-availability');

Route::prefix('users')->name('users.')->middleware(['auth'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index'); // แสดงรายชื่อผู้ใช้ทั้งหมด
    Route::get('/create', [UserController::class, 'create'])->name('create'); // ฟอร์มเพิ่มผู้ใช้
    Route::post('/', [UserController::class, 'store'])->name('store'); // บันทึกผู้ใช้ใหม่
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit'); // ฟอร์มแก้ไขผู้ใช้
    Route::put('/{user}', [UserController::class, 'update'])->name('update'); // อัปเดตผู้ใช้
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy'); // ลบผู้ใช้
});

Route::prefix('meetingRooms')->name('meetingRooms.')->middleware(['auth'])->group(function () {
    Route::get('/', [MeetingRoomController::class, 'index'])->name('index'); // แสดงรายชื่อห้องประชุมทั้งหมด
    Route::get('/create', [MeetingRoomController::class, 'create'])->name('create'); // ฟอร์มเพิ่มห้องประชุม
    Route::post('/', [MeetingRoomController::class, 'store'])->name('store'); // บันทึกห้องประชุมใหม่
    Route::get('/{meetingRoom}/edit', [MeetingRoomController::class, 'edit'])->name('edit'); // ฟอร์มแก้ไขห้องประชุม
    Route::put('/{meetingRoom}', [MeetingRoomController::class, 'update'])->name('update'); // อัปเดตห้องประชุม
    Route::delete('/{meetingRoom}', [MeetingRoomController::class, 'destroy'])->name('destroy'); // ลบห้องประชุม
});

Route::get('/registrations/export-pdf', [RegistrationController::class, 'exportPdf'])->name('registrations.exportPdf');

Route::get('/reports/select', [ReportController::class, 'select'])->name('reports.select');
Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');