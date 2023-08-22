<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends Controller
{
    public function index(Request $request){
        $access_token = $request->bearerToken();
        $user=User::where('access_token',$access_token)->first();
        $addressess= Address::where('user_id',$user->id)->get();
        return response()->json(AddressResource::collection($addressess));
    }

    public function show(Request $request,$id){
        $access_token = $request->bearerToken();
        $user=User::where('access_token',$access_token)->first();
        $address=Address::find($id );
        if(!$address){
            return response()->json(['message' => 'Address not found'], 404);
        }
        if ($user->id == $address->user_id ) {
            $address= new AddressResource($address);
            return response()->json($address);
        }else {
            return response()->json(['message' => 'You do not have access to show this Address'], 403);
        }

    }

    public function store(Request $request) {
        $access_token = $request->bearerToken();
        $validator = Validator::make($request->all(), [
            'country' => 'required|max:255',
            'city' => 'required|max:255',
            'street' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::where('access_token', $access_token)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $address = Address::create([
            'country' => $request->country,
            'city' => $request->city,
            'street' => $request->street,
            'user_id' => $user->id,
        ]);

        $data = [
            'country' => $address->country,
            'city' => $address->city,
            'street' => $address->street,
            'user' => $address->user->name,
        ];

        return response()->json($data, 201);
    }

    public function update(Request $request,$id){
        $access_token = $request->bearerToken();
        $validator = Validator::make($request->all(), [
            'country' => 'required|max:255',
            'city' => 'required|max:255',
            'street' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $user = User::where('access_token', $access_token)->first();
        $address = Address::find($id);
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }
        if ($user->id == $address->user_id) {

            $address->update([
                'country' => $request->country,
                'city' => $request->city,
                'street' => $request->street,
            ]);
            $data = [
                'id' => $request->id,
                'country' => $address->country,
                'city' => $address->city,
                'street' => $address->street,
                'user' => $address->user->name,
            ];

            return response()->json($data);
        } else {
            return response()->json(['message' => 'You do not have access to update this Address'], 403);
        }
    }

    public function delete(Request $request,$id){
        $access_token = $request->bearerToken();
        $user = User::where('access_token', $access_token)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $address = Address::find($id);
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }
        if ($user->id == $address->user_id) {
            $address->delete();
            return response()->json(['message' => 'Address deleted successfully']);
        } else {
            return response()->json(['message' => 'You do not have access to delete this Address'], 403);
        }
    }
}
