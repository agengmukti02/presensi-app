<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model {
  protected $fillable = ['nip','nama','pangkat','golongan','jabatan','status_pegawai','kedudukan'];
  public function user() { 
    return $this->belongsTo(User::class); 
}
  public function attendances() { 
    return $this->hasMany(Attendance::class); 
}
  public function leaveRequests(){
    return $this->hasMany(LeaveRequest::class); 
}
}

