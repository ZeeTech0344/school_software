<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreatePaper extends Model
{
    use HasFactory;

    protected $fillable = [
        'connect_teacher_id',
        'create_exam_id',
        'marks',
        'session_id'

    ];

    public function getTeacherConnectedData()
    {
        return $this->belongsTo(ConnectTeacherStudentBook::class, "connect_teacher_id");
    }

    function exam(){
        return $this->belongsTo(CreateExam::class, "create_exam_id");
    }

}
