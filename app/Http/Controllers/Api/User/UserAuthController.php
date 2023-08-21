<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAuthController extends Controller
{

    public function register(Request $request){

        $valdiator=Validator::make($request->all(),[
            'name'=>'required|string|max:100',
            'email'=>'required|email|max:100',
            'password'=>'required|string|max:100|min:8',
            'phone'=>'required|max:100|min:8',
            'image'=>'required|image|mimes:png,jpg,jpeg',
        ]);

        if ($valdiator->fails()) {
            $errors=$valdiator->errors();
            return response($errors);
        }
        if(User::where('email',$request->email)->first()){
            return response()->json('this email is exsist');
        }

        $image=$request->image;
        $ext=$image->getClientOriginalExtension();
        $new_name=uniqid() . '.' . $ext;
        $image->move(public_path('images/users'),$new_name);

        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'password'=>Hash::make($request->password),
            'image'=>$new_name,
            'access_token'=>Str::random(64),
        ]);
        $data=[
            'name'=>$user->name,
            'email'=>$user->email,
            'phone'=>$user->phone,
            'image'=>"public/images/users/$user->image",
            'access_token'=>$user->access_token,
        ];
        return response()->json($data);
    }

    //Login
    public function login(Request $request){

        $valdiator=Validator::make($request->all(),[
            'email'=>'required|email|max:100',
            'password'=>'required|string|max:100|min:8',
        ]);

        if ($valdiator->fails()) {
            $errors=$valdiator->errors();
            return response($errors);
        }
        $user=auth()->attempt(['email'=>$request->email,'password'=>$request->password]);
        if(!$user){
            return response()->json('no match record');
        }
        $user=User::where('email',$request->email)->first();;
        $user->update([
            'access_token'=>Str::random(64),
        ]);
            $data=[
            'name'=>$user->name,
            'email'=>$user->email,
            'phone'=>$user->phone,
            'image'=>"public/images/users/$user->image",
            'access_token'=>$user->access_token,
        ];
        return response()->json($data);
    }

    public function logout(Request $request){
        $accessToken = $request->bearerToken();
        $seller = User::where('access_token', $accessToken)->first();

        if (!$seller) {
            return response()->json(['message' => 'Invalid access token'], 401);
        }

        $seller->update([
            'access_token' => null,
        ]);

        return response()->json(['message' => 'Logout successful'], 200);
    }
}
