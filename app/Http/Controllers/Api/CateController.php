<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cate;
use Illuminate\Http\Request;

class CateController extends Controller
{
    public function all_cate(){
        $cates=Cate::all();
        return response()->json($cates);
    }
}
