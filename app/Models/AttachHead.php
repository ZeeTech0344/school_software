<?php

namespace App\Models;

use FontLib\Table\Type\head;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttachHead extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'head_id',
        'amount',
        'session_id'
    ];

    public function getClass()
    {
        return $this->belongsTo(classes::class, "class_id");
    }

    public function getHead()
    {
        return $this->belongsTo(VoucherHead::class, "head_id");
    }

}
