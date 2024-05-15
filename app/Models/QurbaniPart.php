<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbaniPart extends Model
{
    use HasFactory;


    protected $fillable = [
        'qurbani_id',
        'total_parts',
        'total_parts_amount',
        'remarks'
    ];

    function getQurbaniInfo()
    {
        return $this->belongsTo(Qurbani::class, "qurbani_id");
    }
}
