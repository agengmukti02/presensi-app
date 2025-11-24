<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        
        // Generate attendance data untuk bulan ini (30 hari terakhir)
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        foreach ($employees as $employee) {
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // Skip weekend (Sabtu & Minggu)
                if ($date->isWeekend()) {
                    continue;
                }

                // Random status dengan probabilitas:
                // 85% hadir, 5% sakit, 5% izin, 3% dl, 2% dd
                $random = rand(1, 100);
                
                if ($random <= 85) {
                    $status = 'hadir';
                    $timeIn = $date->copy()->setTime(rand(7, 9), rand(0, 59));
                    $note = null;
                } elseif ($random <= 90) {
                    $status = 'sakit';
                    $timeIn = null;
                    $note = 'Sakit';
                } elseif ($random <= 95) {
                    $status = 'izin';
                    $timeIn = null;
                    $note = 'Izin keperluan keluarga';
                } elseif ($random <= 98) {
                    $status = 'dl';
                    $timeIn = null;
                    $note = 'Dinas luar';
                } else {
                    $status = 'dd';
                    $timeIn = null;
                    $note = 'Dinas dalam';
                }

                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->format('Y-m-d'),
                    'time_in' => $timeIn ? $timeIn->format('H:i:s') : null,
                    'status' => $status,
                    'note' => $note,
                ]);
            }
        }
    }
}
