<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;

// หน้าแสดงแบบฟอร์มอัปโหลด
Route::get('/', [ScheduleController::class, 'index'])->name('upload.index');

// อัปโหลดและประมวลผลไฟล์
Route::post('/upload', [ScheduleController::class, 'processSchedules'])->name('upload.store');

Route::post('/process-schedules', [ScheduleController::class, 'processSchedules'])->name('processSchedules');