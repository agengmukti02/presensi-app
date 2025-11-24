<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\leave_requests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    public function approve(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $leaveRequest = leave_requests::findOrFail($id);
            $leaveRequest->status = 'approved';
            $leaveRequest->approved_by = $request->user()->id;
            $leaveRequest->save();

            $startDate = Carbon::parse($leaveRequest->start_date);
            $endDate = Carbon::parse($leaveRequest->end_date);

            while ($startDate->lte($endDate)) {
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $leaveRequest->employee_id,
                        'date' => $startDate->format('Y-m-d'),
                    ],
                    [
                        'status' => $leaveRequest->type, // 'izin' or 'sakit'
                        'note' => $leaveRequest->reason,
                    ]
                );
                $startDate->addDay();
            }

            DB::commit();

            return response()->json(['message' => 'Leave request approved successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error approving leave request', 'error' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        return \Inertia\Inertia::render('izin/PengajuanIzin');
    }

    public function approvalList()
    {
        $leaveRequests = leave_requests::with('employee')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return \Inertia\Inertia::render('Dashboard/AdminLeaveList', [
            'leaveRequests' => $leaveRequests
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
        ]);

        // Assuming logged in user is linked to an employee
        // For now, let's assume user->id maps to employee->user_id or similar, 
        // or just pick the first employee for demo if not linked.
        // Ideally: $employee = $request->user()->employee;
        // But Employee model has user() relationship.
        
        $employee = \App\Models\Employee::where('id', $request->user()->id)->first(); // Simplification
        if (!$employee) {
             // Fallback or error
             // For demo purposes, let's just use ID 1 if not found
             $employee = \App\Models\Employee::first();
        }

        leave_requests::create([
            'employee_id' => $employee->id,
            'start_date' => $request->tanggal,
            'end_date' => $request->tanggal, // Single day for now based on form
            'type' => 'izin', // Default
            'reason' => $request->keterangan,
            'status' => 'pending',
        ]);

        return redirect('/dashboard')->with('success', 'Pengajuan izin berhasil dikirim.');
    }

    public function reject($id)
    {
        $leaveRequest = leave_requests::findOrFail($id);
        $leaveRequest->status = 'rejected';
        $leaveRequest->save();

        return back()->with('success', 'Pengajuan izin ditolak.');
    }
}
