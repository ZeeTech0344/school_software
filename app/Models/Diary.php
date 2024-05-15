<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    use HasFactory;

    protected $fillable = [
        'attach_book_id',
        'teacher_id',
        'student_id',
        'description'
    ];

    public function getAttachBooks()
    {

        return $this->belongsTo(ConnectTeacherStudentBook::class, "attach_book_id");
    }

    public function getTeachers()
    {
        return $this->belongsTo(User::class, "teacher_id");
    }

    public function getStudent()
    {
        return $this->belongsTo(Admission::class, "student_id");
    }

    public function getAttendances()
    {
        return $this->hasMany(StudentAttendance::class,  'student_id', 'student_id');
    }
    

}


