<?php

use App\Http\Controllers\Admin\AssistantController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\JadwalController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [JadwalController::class, 'index'])->name('jadwal');

// Admin Auth
Route::get('/admin/login', [AuthController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin Panel
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('schedules', ScheduleController::class)->except(['show']);
    Route::resource('courses', CourseController::class)->except(['show']);
    Route::resource('assistants', AssistantController::class)->except(['show']);
});
