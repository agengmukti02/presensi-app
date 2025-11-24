<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportExcel($month, $year)
    {
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        
        // Prepare Headings
        $headings = ['No', 'Nama', 'NIP'];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $headings[] = (string)$i;
        }

        // Fetch Data
        $employees = Employee::with(['attendances' => function ($query) use ($month, $year) {
            $query->whereMonth('date', $month)
                  ->whereYear('date', $year);
        }])->get();

        $rows = $employees->map(function ($employee, $index) use ($daysInMonth) {
            $attendanceMap = $employee->attendances->keyBy(function ($attendance) {
                return (int) Carbon::parse($attendance->date)->format('d');
            });

            $row = [
                $index + 1,
                $employee->nama,
                $employee->nip,
            ];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                if (isset($attendanceMap[$day])) {
                    $att = $attendanceMap[$day];
                    if ($att->status === 'hadir') {
                        $row[] = $att->time_in ? Carbon::parse($att->time_in)->format('H:i') : 'Hadir';
                    } else {
                        $row[] = $att->status;
                    }
                } else {
                    $row[] = '-';
                }
            }

            return $row;
        });

        return Excel::download(new AttendanceExport($rows, $headings), "presensi_{$month}_{$year}.xlsx");
    }

    public function exportPdf($month, $year)
    {
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        $days = range(1, $daysInMonth);

        $employees = Employee::with(['attendances' => function ($query) use ($month, $year) {
            $query->whereMonth('date', $month)
                  ->whereYear('date', $year);
        }])->get();

        $rows = $employees->map(function ($employee, $index) use ($daysInMonth) {
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('attendance_table', [
            'month' => $month,
            'year' => $year,
            'days' => $days,
            'rows' => $rows
        ]);

        return $pdf->setPaper('a4', 'landscape')->download("presensi_{$month}_{$year}.pdf");
    }
}
