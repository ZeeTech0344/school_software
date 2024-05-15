<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObtainedMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'create_paper_id',
        'student_id',
        'marks',
        'session_id'
    ];


    public function admission()
    {

        return $this->belongsTo(Admission::class, "student_id");
    }

    public function createPaper()
    {
        return $this->belongsTo(createPaper::class, "create_paper_id");
    }

    

}
