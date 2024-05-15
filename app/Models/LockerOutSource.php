<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockerOutSource extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "amount",
        "remarks",
        "amount_status",
        "is_this_outsource_locker"
    ];
}
