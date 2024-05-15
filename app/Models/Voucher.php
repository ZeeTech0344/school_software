<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_no',
        'class_id',
        'student_id',
        'voucher_heads',
        'for_the_month',
        'last_date',
        'session_id'
    ];


    public function classes()
    {
    return $this->belongsTo(classes::class , "class_id");
    }


    public function admissions()
    {
    return $this->hasMany(Admission::class,  "id", "student_id");
    }

    public function singleAdmission()
    {
    return $this->belongsTo(Admission::class,  "student_id");
    }


    public function getClass()
    {
        return $this->admissions()->belongsTo(classes::class, 'class_id');
    }




}
