<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Seller extends  Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'image',
        "access_token",
        "oauth_token",
    ];

    public function products(){
        return $this->hasMany('App\Models\Product');
    }
    public function events(){
        return $this->hasMany('App\Models\Product');
    }

    public function copons(){
        return $this->hasMany('App\Models\Copon');
    }
}
