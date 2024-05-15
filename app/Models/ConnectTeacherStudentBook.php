<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectTeacherStudentBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'class_id',
        'book_id'

    ];

    public function getBooks()
    {

        return $this->belongsTo(Book::class, "book_id");
    }

    public function getTeachers()
    {

        return $this->belongsTo(User::class, "teacher_id");
    }

    public function getClasses()
    {

        return $this->belongsTo(classes::class, "class_id");
    }
}
