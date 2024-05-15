<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class classes extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'class',
        'department_id',
        'status'
    ];

    public function getDepartments()
    {
        return $this->belongsTo(Department::class, "department_id");
    }
}
