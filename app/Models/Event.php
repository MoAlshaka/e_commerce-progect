<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','desc','image','original_price','price_after_discount','stock','tag','seller_id','cate_id',
        'start_date','end_date',
    ];

    public function seller(){
        return $this->belongsTo('App\Models\Seller');
    }

    public function cate(){
        return $this->belongsTo('App\Models\Cate');
    }
}
