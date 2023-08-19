<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Copon extends Model
{
    use HasFactory;

    protected $fillable = [
        'copon_code','rate_of_discount','max_amount','min_amount','product_id','seller_id',
    ];

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }

    public function seller(){
        return $this->belongsTo('App\Models\Seller');
    }
}
