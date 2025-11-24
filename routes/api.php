<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\EmployeeController;

// Employees
Route::get('/employees', [EmployeeController::class,'index']);
Route::get('/employees/{id}', [EmployeeController::class,'show']);

// Attendances
Route::get('/attendances/report/{month}/{year}', [AttendanceController::class,'monthlyReport']);
Route::post('/attendances', [AttendanceController::class,'store']);
Route::put('/attendances/{id}', [AttendanceController::class,'update']);

// Leave requests
Route::post('/leave-requests', [LeaveRequestController::class,'store']);
Route::get('/leave-requests', [LeaveRequestController::class,'index']); // admin sees all
Route::put('/leave-requests/{id}/approve', [LeaveRequestController::class,'approve']);
Route::put('/leave-requests/{id}/reject', [LeaveRequestController::class,'reject']);

// Export
Route::get('/attendances/export/excel/{month}/{year}', [ExportController::class,'exportExcel']);
Route::get('/attendances/export/pdf/{month}/{year}', [ExportController::class,'exportPdf']);

// 
Route::get('/attendances/report/{month}/{year}', [AttendanceController::class,'monthlyReport'])->middleware('auth:sanctum');
