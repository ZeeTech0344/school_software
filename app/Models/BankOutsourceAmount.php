<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankOutsourceAmount extends Model
{
    use HasFactory;
    protected $fillable = [
        "invoice_no",
        "given_by",
        "address",
        "phone_no",
         "fund_type",
        "payment_type",
         "amount",
         "remarks",
         "recieved_by",
        "amount_status"
    ];

    
    public function getEmployee()
    {
    return $this->belongsTo(User::class , "recieved_by");
    }
          

}
