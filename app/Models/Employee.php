<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
  use HasFactory;

  protected $fillable = ['user_id','nip','nama','pangkat','golongan','jabatan','status_pegawai','kedudukan'];
  
  public function user()
  { 
    return $this->belongsTo(User::class, 'user_id', 'id'); 
  }
  
  public function attendances()
  { 
    return $this->hasMany(Attendance::class); 
  }
  
  public function leaveRequests()
  {
    return $this->hasMany(LeaveRequest::class); 
  }
}

