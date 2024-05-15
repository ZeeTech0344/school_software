<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EasypaisaOutSource extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "amount",
        "remarks",
        "amount_status",
        "invoice_no",
        "recieved_by",
        "given_by"
    ];

    public function getEmployee()
    {
    return $this->belongsTo(User::class , "recieved_by");
    }

}
