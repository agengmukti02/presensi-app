<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [AttendanceController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','role:admin'])->group(function(){
   Route::get('/presensi/admin', [AttendanceController::class, 'presensiAdmin'])->name('presensi.admin');
   Route::get('/izin/create', [LeaveRequestController::class, 'create'])->name('izin.create');
   Route::post('/izin/store', [LeaveRequestController::class, 'store'])->name('izin.store');
   Route::get('/izin/approval', [LeaveRequestController::class, 'approvalList'])->name('izin.approval');
   Route::put('/izin/approve/{id}', [LeaveRequestController::class, 'approve'])->name('izin.approve');
   Route::put('/izin/reject/{id}', [LeaveRequestController::class, 'reject'])->name('izin.reject');
});

Route::middleware(['auth','role:pegawai'])->group(function(){
   Route::get('/presensi/pegawai', [AttendanceController::class, 'presensiPegawai'])->name('presensi.pegawai');
   Route::get('/izin/create', [LeaveRequestController::class, 'create'])->name('izin.create');
   Route::post('/izin/store', [LeaveRequestController::class, 'store'])->name('izin.store');
});

require __DIR__.'/auth.php';


