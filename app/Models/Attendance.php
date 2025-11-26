<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = ['employee_id', 'date', 'time_in', 'time_out', 'status', 'note'];

    /**
     * Relasi ke Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Scope untuk mengambil attendance hari ini berdasarkan employee_id
     */
    public function scopeTodayAttendance($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId)
                     ->whereDate('date', Carbon::today());
    }

    /**
     * Cek apakah employee sudah check-in hari ini
     */
    public static function hasCheckedInToday($employeeId)
    {
        return self::todayAttendance($employeeId)->exists();
    }

    /**
     * Ambil data attendance hari ini untuk employee tertentu
     */
    public static function getTodayAttendance($employeeId)
    {
        return self::todayAttendance($employeeId)->first();
    }
}
