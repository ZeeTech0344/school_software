<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admission extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'admission_year',
        'register_no',
        'roll_no',
        'admission_date',
        'class_id',
        'section',
        'shift',
        'category',
        'name',
        'father_name',
        'dob',
        'mobile_no',
        'address',
        'image',
        'status'
    ];


    public function getClass()
    {
        return $this->belongsTo(classes::class, "class_id");
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class,  "student_id", "id");
    }

    public function fine()
    {
        return $this->hasMany(Fine::class,  "student_id", "id");
    }


    public function marks()
    {
        return $this->hasMany(ObtainedMark::class,  "student_id", "id");
    }

    
    public function attendance()
    {
        return $this->hasMany(StudentAttendance::class,  "student_id", "id");
    }

    public function getOneVoucher()
    {
        return $this->hasOne(Voucher::class,  "student_id", "id");
    }

   
   
}
