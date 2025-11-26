<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        
        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return $this->adminDashboard($request);
        } elseif ($user->role === 'pegawai') {
            return $this->pegawaiDashboard($request);
        } else {
            abort(403, 'Role tidak dikenali.');
        }
    }
    
    private function adminDashboard(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        
        // Hitung statistik untuk hari ini atau tanggal yang dipilih
        $totalPegawai = Employee::count();
        
        // Hitung pegawai hadir
        $hadir = \App\Models\Attendance::whereDate('date', $selectedDate)
            ->where('status', 'hadir')
            ->count();
        
        // Hitung pegawai sakit, izin dari attendance
        $sakit = \App\Models\Attendance::whereDate('date', $selectedDate)
            ->where('status', 'sakit')
            ->count();
            
        $izin = \App\Models\Attendance::whereDate('date', $selectedDate)
            ->where('status', 'izin')
            ->count();
        
        // Hitung leave requests yang approved untuk hari ini
        $leaveRequests = \App\Models\LeaveRequest::where('status', 'approved')
            ->whereDate('start_date', '<=', $selectedDate)
            ->whereDate('end_date', '>=', $selectedDate)
            ->get();
        
        $izinApproved = $leaveRequests->where('type', 'izin')->count();
        $sakitApproved = $leaveRequests->where('type', 'sakit')->count();
        $dinasDalam = $leaveRequests->where('type', 'dd')->count();
        $dinasLuar = $leaveRequests->where('type', 'dl')->count();
        
        // Total izin dan sakit (dari attendance + leave request)
        $totalIzin = $izin + $izinApproved;
        $totalSakit = $sakit + $sakitApproved;
        
        // Hitung yang tidak hadir (tidak ada record)
        $tidakHadir = $totalPegawai - ($hadir + $totalSakit + $totalIzin + $dinasDalam + $dinasLuar);
        
        return \Inertia\Inertia::render('Dashboard/AdminDashboard', [
            'stats' => [
                'totalPegawai' => $totalPegawai,
                'hadir' => $hadir,
                'sakit' => $totalSakit,
                'izin' => $totalIzin,
                'dinasDalam' => $dinasDalam,
                'dinasLuar' => $dinasLuar,
                'tidakHadir' => $tidakHadir,
            ],
            'selectedDate' => $selectedDate->format('Y-m-d'),
        ]);
    }
    
    private function pegawaiDashboard(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        $user = auth()->user();
        
        // Cek apakah user memiliki data employee
        if (!$user->employee) {
            // Redirect ke halaman error atau tampilkan pesan
            return redirect()->back()->withErrors([
                'error' => 'Anda tidak terdaftar sebagai pegawai. Silakan hubungi administrator.'
            ]);
        }
        
        $employee = $user->employee;
        
        $data = $this->getReportDataForEmployee($employee->id, $month, $year);
        
        // Hitung statistik pribadi
        $stats = $this->getEmployeeStats($employee->id, $month, $year);

        return \Inertia\Inertia::render('Dashboard/PegawaiDashboard', [
            'days' => $data['days'],
            'rows' => $data['rows'],
            'currentMonth' => (int)$month,
            'currentYear' => (int)$year,
            'employee' => $employee,
            'stats' => $stats,
        ]);
    }
    
    private function getEmployeeStats($employeeId, $month, $year)
    {
        // Hitung total hari dalam bulan
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        
        // Ambil semua attendance untuk bulan ini
        $attendances = \App\Models\Attendance::where('employee_id', $employeeId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();
        
        // Hitung statistik
        $hadir = $attendances->where('status', 'hadir')->count();
        $sakit = $attendances->where('status', 'sakit')->count();
        $izin = $attendances->where('status', 'izin')->count();
        $dinasDalam = $attendances->where('status', 'dd')->count();
        $dinasLuar = $attendances->where('status', 'dl')->count();
        
        // Hitung keterlambatan (hadir setelah jam 08:00)
        $terlambat = $attendances->filter(function ($att) {
            if ($att->status === 'hadir' && $att->time_in) {
                $timeIn = Carbon::parse($att->time_in);
                $cutoffTime = Carbon::parse($att->date)->setTime(8, 0, 0);  // Jam 08:00 pagi untuk set batas waktu telat
                return $timeIn->greaterThan($cutoffTime);
            }
            return false;
        })->count();
        
        // Hitung tidak hadir (hari kerja - semua kehadiran)
        // Asumsi: Sabtu & Minggu bukan hari kerja
        $workDays = 0;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            if (!$date->isWeekend()) {
                $workDays++;
            }
        }
        
        $totalKehadiran = $hadir + $sakit + $izin + $dinasDalam + $dinasLuar;
        $tidakHadir = max(0, $workDays - $totalKehadiran);
        
        // Hitung persentase kehadiran
        $persentaseKehadiran = $workDays > 0 ? round(($hadir / $workDays) * 100, 1) : 0;
        
        return [
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'dinasDalam' => $dinasDalam,
            'dinasLuar' => $dinasLuar,
            'terlambat' => $terlambat,
            'tidakHadir' => $tidakHadir,
            'totalHariKerja' => $workDays,
            'persentaseKehadiran' => $persentaseKehadiran,
        ];
    }

    public function presensiPegawai(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        $user = auth()->user();
        
        if (!$user->employee) {
            abort(403, 'Anda tidak terdaftar sebagai pegawai.');
        }
        
        $employee = $user->employee;
        
        $data = $this->getReportDataForEmployee($employee->id, $month, $year);
        
        return \Inertia\Inertia::render('Dashboard/PresensiPegawai', [
            'days' => $data['days'],
            'rows' => $data['rows'],
            'currentMonth' => (int)$month,
            'currentYear' => (int)$year,
            'employee' => $employee,
        ]);
    }
    
    public function presensiAdmin(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $employeeId = $request->input('employee_id');
        
        $employees = Employee::orderBy('nama')->get();
        
        if ($employeeId) {
            $data = $this->getReportDataForEmployee($employeeId, $month, $year);
        } else {
            $data = $this->getReportData($month, $year);
        }
        
        return \Inertia\Inertia::render('Dashboard/PresensiAdmin', [
            'days' => $data['days'],
            'rows' => $data['rows'],
            'currentMonth' => (int)$month,
            'currentYear' => (int)$year,
            'employees' => $employees,
            'selectedEmployeeId' => $employeeId ? (int)$employeeId : null,
        ]);
    }

    public function monthlyReport($month, $year)
    {
        return response()->json($this->getReportData($month, $year));
    }
    
    private function getReportDataForEmployee($employeeId, $month, $year)
    {
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        $days = range(1, $daysInMonth);

        $employee = Employee::with(['attendances' => function ($query) use ($month, $year) {
            $query->whereMonth('date', $month)
                  ->whereYear('date', $year);
        }])->findOrFail($employeeId);

        $attendanceMap = $employee->attendances->keyBy(function ($attendance) {
            return (int) Carbon::parse($attendance->date)->format('d');
        });

        $row = [
            'no' => 1,
            'name' => $employee->nama,
            'nip' => $employee->nip,
        ];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $key = 'd' . $day;
            if (isset($attendanceMap[$day])) {
                $att = $attendanceMap[$day];
                if ($att->status === 'hadir') {
                    $row[$key] = $att->time_in ? Carbon::parse($att->time_in)->format('H:i') : 'Hadir';
                } else {
                    $row[$key] = $att->status;
                }
            } else {
                $row[$key] = null;
            }
        }

        return [
            'days' => $days,
            'rows' => [$row],
        ];
    }

    private function getReportDataForEmployee($employeeId, $month, $year)
    {
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        $days = range(1, $daysInMonth);

        $employee = Employee::with(['attendances' => function ($query) use ($month, $year) {
            $query->whereMonth('date', $month)
                ->whereYear('date', $year);
        }])->findOrFail($employeeId);

        $attendanceMap = $employee->attendances->keyBy(function ($attendance) {
            return (int) Carbon::parse($attendance->date)->format('d');
        });

        $row = [
            'no' => 1,
            'name' => $employee->nama,
            'nip' => $employee->nip,
        ];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $key = 'd' . $day;
            if (isset($attendanceMap[$day])) {
                $att = $attendanceMap[$day];
                if ($att->status === 'hadir') {
                    $row[$key] = $att->time_in ? Carbon::parse($att->time_in)->format('H:i') : 'Hadir';
                } else {
                    $row[$key] = $att->status;
                }
            } else {
                $row[$key] = null;
            }
        }

        return [
            'days' => $days,
            'rows' => [$row],
        ];
    }

    private function getReportData($month, $year)
    {
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        $days = range(1, $daysInMonth);

        $employees = Employee::with(['attendances' => function ($query) use ($month, $year) {
            $query->whereMonth('date', $month)
                ->whereYear('date', $year);
        }])->get();

        $rows = $employees->map(function ($employee, $index) use ($daysInMonth, $year, $month) {
            $attendanceMap = $employee->attendances->keyBy(function ($attendance) {
                return (int) Carbon::parse($attendance->date)->format('d');
            });

            $row = [
                'no' => $index + 1,
                'name' => $employee->nama,
                'nip' => $employee->nip,
            ];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $key = 'd' . $day;
                if (isset($attendanceMap[$day])) {
                    $att = $attendanceMap[$day];
                    if ($att->status === 'hadir') {
                        $row[$key] = $att->time_in ? Carbon::parse($att->time_in)->format('H:i') : 'Hadir';
                    } else {
                        $row[$key] = $att->status;
                    }
                } else {
                    $row[$key] = null;
                }
            }

            return $row;
        });

        return [
            'days' => $days,
            'rows' => $rows,
        ];
    }

    /**
     * Handle check-in (presensi hadir)
     */
    public function checkIn(Request $request)
    {
        $user = auth()->user();

        // Validasi user memiliki data employee
        if (!$user->employee) {
            return back()->withErrors(['message' => 'Anda tidak terdaftar sebagai pegawai.']);
        }

        $employee = $user->employee;

        // Cek apakah sudah check-in hari ini
        if (Attendance::hasCheckedInToday($employee->id)) {
            return back()->withErrors(['message' => 'Anda sudah hadir hari ini.']);
        }

        try {
            // Ambil waktu sekarang saat tombol diklik
            $now = Carbon::now('Asia/Jakarta'); // Set timezone Indonesia (ubah sesuai kebutuhan)

            // Default jam pulang: 16:00 (ubah sesuai kebutuhan)
            $defaultCheckoutTime = '16:00:00';

            // Buat record attendance baru dengan time_out default
            $attendance = Attendance::create([
                'employee_id' => $employee->id,
                'date' => $now->format('Y-m-d'),
                'time_in' => $now->format('H:i:s'),
                'time_out' => $defaultCheckoutTime, // Set default jam pulang
                'status' => 'hadir',
            ]);

            return back()->with([
                'flash' => [
                    'success' => true,
                    'message' => 'Presensi berhasil dicatat.',
                ]
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Terjadi kesalahan saat menyimpan presensi: ' . $e->getMessage()]);
        }
    }

    /**
     * Cek status presensi hari ini
     */
    public function statusHariIni(Request $request)
    {
        $user = auth()->user();

        if (!$user->employee) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak terdaftar sebagai pegawai.'
            ], 403);
        }

        $employee = $user->employee;
        $todayAttendance = Attendance::getTodayAttendance($employee->id);

        return response()->json([
            'success' => true,
            'hasCheckedIn' => $todayAttendance !== null,
            'attendance' => $todayAttendance,
            'time_in_formatted' => $todayAttendance
                ? Carbon::parse($todayAttendance->time_in)->format('H:i')
                : null,
        ]);
    }
}
