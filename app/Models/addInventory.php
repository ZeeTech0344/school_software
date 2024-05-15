<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class addInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'quantity',
        'total_amount',
        'remarks'
    ];

    public function getProducts()
    {
    return $this->belongsTo(addProduct::class , "product_id");
    }
}
