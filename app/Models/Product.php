<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','desc','react','image','original_price','price_after_discount','stock','tag','seller_id','cate_id'
    ];

    public function seller(){
        return $this->belongsTo('App\Models\Seller');
    }

    public function cate(){
        return $this->belongsTo('App\Models\Cate');
    }

    public function copon(){
        return $this->hasOne('App\Models\Copon');
    }
}
