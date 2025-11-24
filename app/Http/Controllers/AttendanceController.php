<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function dashboard(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $data = $this->getReportData($month, $year);

        return \Inertia\Inertia::render('Dashboard/Dashboard', [
            'days' => $data['days'],
            'rows' => $data['rows'],
            'currentMonth' => (int)$month,
            'currentYear' => (int)$year,
        ]);
    }

    public function monthlyReport($month, $year)
    {
        return response()->json($this->getReportData($month, $year));
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
}
