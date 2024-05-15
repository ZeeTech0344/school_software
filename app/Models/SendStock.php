<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendStock extends Model
{
    use HasFactory;


    protected $fillable = [
        'date',
        'stock_id',
        'vendor_id',
        'item_id',
        'stock_qty_send',
        'branch'
    ];



    public function getStock()
    {
    return $this->belongsTo(addStock::class , "stock_id");
    }

    public function getVendors()
    {
    return $this->belongsTo(addVendor::class , "vendor_id");
    }

    public function getItems()
    {
    return $this->belongsTo(addItem::class , "item_id");
    }


}
