<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    public function approve(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $leaveRequest = LeaveRequest::findOrFail($id);
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

            return back()->with('success', 'Pengajuan izin berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return \Inertia\Inertia::render('izin/PengajuanIzin');
    }

    public function approvalList()
    {
        $leaveRequests = LeaveRequest::with(['employee', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return \Inertia\Inertia::render('Dashboard/AdminLeaveList', [
            'leaveRequests' => $leaveRequests
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:izin,sakit,dd,dl',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:500',
        ]);

        $employee = \App\Models\Employee::where('id', $request->user()->id)->first();
        if (!$employee) {
             return back()->withErrors(['message' => 'Anda tidak terdaftar sebagai pegawai.']);
        }

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'start_date' => $request->tanggal,
            'end_date' => $request->tanggal,
            'type' => $request->tipe,
            'reason' => $request->keterangan,
            'status' => 'pending',
        ]);

        $tipeName = [
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'dd' => 'Dinas Dalam',
            'dl' => 'Dinas Luar'
        ][$request->tipe];

        return redirect('/dashboard')->with('success', "Pengajuan {$tipeName} berhasil dikirim.");
    }

    public function reject(Request $request, $id)
    {
        try {
            $leaveRequest = LeaveRequest::findOrFail($id);
            $leaveRequest->status = 'rejected';
            $leaveRequest->approved_by = $request->user()->id;
            $leaveRequest->save();

            return back()->with('success', 'Pengajuan izin berhasil ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
