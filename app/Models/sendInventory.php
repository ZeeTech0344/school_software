<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sendInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'branch',
        'product_id',
        'send_qty',
        'remarks'
    ];


    public function getProducts()
    {
    return $this->belongsTo(addProduct::class , "product_id");
    }
}
