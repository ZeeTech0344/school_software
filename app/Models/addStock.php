<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class addStock extends Model
{
    use HasFactory;


    protected $fillable = [
        'vendor_id',
        'item_id',
        'weight',
        'weight_type',
        'current_rate',
        'total_amount',
    ];


    public function getVendors()
    {
    return $this->belongsTo(addVendor::class , "vendor_id");
    }

    public function getItems()
    {
    return $this->belongsTo(addItem::class , "item_id");
    }

    public function getSendRecord(){
        // return $this->hasMany(SendStock::class,"stock_id","id");
        return $this->hasMany(SendStock::class,"stock_id","id");
    }

    public function getReturnRecord(){
        // return $this->hasMany(SendStock::class,"stock_id","id");
        return $this->hasMany(ReturnStock::class,"stock_id","id");
    }

}
