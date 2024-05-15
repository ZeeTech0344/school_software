<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;


    protected $fillable = [
        'class_id',
        'student_id',
        'amount',
        'session_id',
        'for_the_month',
        'remarks'
    ];


    public function admission()
    {

        return $this->belongsTo(Admission::class, "student_id");
    }

    public function class()
    {

        return $this->belongsTo(classes::class, "class_id");
    }


}
