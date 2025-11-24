<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['employee_id', 'date', 'time_in', 'status', 'note'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
