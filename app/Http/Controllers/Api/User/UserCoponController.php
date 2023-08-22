<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\CoponResource;
use App\Models\Copon;
use Illuminate\Http\Request;

class UserCoponController extends Controller
{

    public function all_copon(){
        $copons=Copon::all();
        if(!$copons){
            return response()->json(['message' => ' No Copons founded '], 404);
        }
        return response()->json(CoponResource::collection($copons));
    }

    public function one_copon($id){
        $copon=Copon::find($id);
        if(!$copon){
            return response()->json(['message' => 'Copon not found'], 404);
        }
        return response()->json(new CoponResource($copon));

    }
}
