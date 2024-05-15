<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class addItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'vendor_id',
        'item_name'
    ];


    public function getVendors()
    {
    return $this->belongsTo(addVendor::class , "vendor_id");
    }

    public function totalStock(){
        return $this->hasMany(addStock::class,"item_id","id");
    }

    public function sendStock(){
        return $this->hasMany(SendStock::class,"item_id","id");
    }

    public function returnStock(){
        return $this->hasMany(returnStock::class,"item_id","id");
    }
    
}
