<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    //

    public function update(Request $request){
        $access_token = $request->bearerToken();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'nullable',
            'phone' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $seller = Seller::where('access_token', $access_token)->first();

        if ($seller) {
            $image_name = $seller->image;
            if ($request->hasFile('image')) {
                unlink(public_path("images/sellers/$image_name"));
                $image = $request->file('image');
                $ext = $image->getClientOriginalExtension();
                $image_name = uniqid() . '.' . $ext;
                $image->move(public_path('images/sellers'), $image_name);
            }
            $seller->update([
                'name' => $request->name,
                'email' => $request->email,
                'image' => $image_name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            $data=[
                'name'=>$seller->name,
                'email'=>$seller->email,
                'phone'=>$seller->phone,
                'image'=>"public/images/sellers/$seller->image",
            ];
            return response()->json($data);
        } else {
            return response()->json(['message' => 'You do not have access to update this email'], 403);
        }
    }
}
