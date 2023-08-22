<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable=[
        "country","city","street","user_id"
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
