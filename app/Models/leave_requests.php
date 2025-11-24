<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class leave_requests extends Model
{
    protected $fillable = ['employee_id','start_date','end_date','type','reason','status','approved_by'];
    public function employee(){ return $this->belongsTo(Employee::class); }
    public function approvedBy(){ return $this->belongsTo(User::class); }
}
