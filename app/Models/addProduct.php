<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class addProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'product',
        'status'
    ];


    public function getInventory(){
        return $this->hasMany(addInventory::class,"product_id","id");
    }

    public function getSendInventory(){
        return $this->hasMany(sendInventory::class,"product_id","id");
    }

    public function getReturnInventory(){
        return $this->hasMany(returnInventory::class,"product_id","id");
    }

}
