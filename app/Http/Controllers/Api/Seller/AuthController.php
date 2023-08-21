<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

     //register

     public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'phone' => 'required|max:20',
            'email' => 'required|email|max:100',
            'password' => 'required|string|max:100|min:8',
            'image'=>'required|image|mimes:png,jpg,jpeg',
        ]);

         if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if (Seller::where('email', $request->email)->first()) {
            return response()->json(['message' => 'This email is already registered'], 409);
        }

        $image=$request->image;
        $ext=$image->getClientOriginalExtension();
        $new_name=uniqid() . '.' . $ext;
        $image->move(public_path('images/sellers'),$new_name);

        $seller = Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'image'=>$new_name,
            'access_token' => Str::random(64),
        ]);
        $data=[
            'name'=>$seller->name,
            'email'=>$seller->email,
            'phone'=>$seller->phone,
            'image'=>"public/images/sellers/$seller->image",
            'access_token'=>$seller->access_token,
        ];

        return response()->json($data, 201); // 201 Created status
    }
    //Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100',
            'password' => 'required|string|max:100|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $attempt = Auth::guard('seller')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$attempt) {
            return response()->json(['message' => 'Authentication failed'], 401);
        }

        $seller = Seller::where('email',$request->email)->first();
        $seller->update(['access_token'=>Str::random(64),
        ]);
        $data=[
            'name'=>$seller->name,
            'email'=>$seller->email,
            'phone'=>$seller->phone,
            'image'=>"public/images/sellers/$seller->image",
            'access_token'=>$seller->access_token,
        ];

        return response()->json($data, 200);
    }

    public function logout(Request $request) {
        $accessToken = $request->bearerToken();
        $seller = Seller::where('access_token', $accessToken)->first();

        if (!$seller) {
            return response()->json(['message' => 'Invalid access token'], 401);
        }

        $seller->update([
            'access_token' => null,
        ]);

        return response()->json(['message' => 'Logout successful'], 200);
    }

}
