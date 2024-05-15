<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class returnInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'branch',
        'product_id',
        'return_qty',
        'remarks'
    ];


    public function getProducts()
    {
    return $this->belongsTo(addProduct::class , "product_id");
    }

}
