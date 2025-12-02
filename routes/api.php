<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LeaveRequestController;

// Employees
// Route::get('/employees', [EmployeeController::class,'index']);
// Route::get('/employees/{id}', [EmployeeController::class,'show']);

// Attendances
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/attendances/report/{month}/{year}', [AttendanceController::class, 'monthlyReport']);
    // Route::post('/attendances', [AttendanceController::class, 'store']); // Method not implemented yet
    // Route::put('/attendances/{id}', [AttendanceController::class, 'update']); // Method not implemented yet

    // Leave requests
    // Route::post('/leave-requests', [LeaveRequestController::class, 'store']); // Uses web auth currently
    // Route::get('/leave-requests', [LeaveRequestController::class, 'index']); // Method not implemented yet
    // Route::put('/leave-requests/{id}/approve', [LeaveRequestController::class, 'approve']); // Uses web auth currently
    // Route::put('/leave-requests/{id}/reject', [LeaveRequestController::class, 'reject']); // Uses web auth currently
});

// Export (untuk admin)
Route::get('/attendances/export/excel/{month}/{year}', [ExportController::class, 'exportExcel']);
Route::get('/attendances/export/pdf/{month}/{year}', [ExportController::class, 'exportPdf']);
