<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendings extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'employee_id',
        'amount',
        'remarks',
        'account_id',
        'account_name'
    ];

    function getStaff(){
        return $this->belongsTo(User::class, "employee_id");
    }

}
