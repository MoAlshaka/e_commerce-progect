<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\CoponResource;
use App\Models\Copon;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CoponController extends Controller
{

    public function index(Request $request){
        $access_token = $request->bearerToken();
        $seller=Seller::where('access_token',$access_token)->first();
        $copons= Copon::where('seller_id',$seller->id)->get();
        return response()->json(CoponResource::collection($copons));

    }

    public function show(Request $request,$id){
        $access_token = $request->bearerToken();
        $seller=Seller::where('access_token',$access_token)->first();
        $copon= new CoponResource(Copon::find($id));
        if(!$copon){
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($copon);
    }

    public function store(Request $request){
        $access_token = $request->bearerToken();
        $validator = Validator ::make($request->all(), [
            'copon_code' => 'required',
            'rate_of_discount' => 'required',
            'max_amount' => 'required',
            'min_amount' => 'required',
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response($errors, 400);
        }

        $seller=Seller::where('access_token',$access_token)->first();
        $product=Product::find($request->product_id);
        if ($seller->id == $product->seller_id) {
            $copon=Copon::create([
                'copon_code'=>$request->copon_code,
                'rate_of_discount'=>$request->rate_of_discount,
                'max_amount'=>$request->max_amount,
                'min_amount'=>$request->min_amount,
                'product_id'=>$request->product_id,
                'seller_id'=>$seller->id,
            ]);
        }else{
            return response()->json(['message' => 'You do not have access to create Copon to this product'], 403);
        }
        $data=[
            'id'=>$copon->id,
            'copon_code'=>$copon->copon_code,
            'rate_of_discount'=>$copon->rate_of_discount,
            'max_amount'=>$copon->max_amount,
            'min_amount'=>$copon->min_amount,
            'product' => $copon->product->name,
            'seller' => $copon->seller->name,
            'created_at'=>$copon->created_at,
            'updated_at'=>$copon->updated_at,
        ];

        return response()->json($data,201);
    }

    public function update(Request $request,$id){
        $access_token = $request->bearerToken();
        $validator = Validator::make($request->all(), [
            'copon_code' => 'required',
            'rate_of_discount' => 'required',
            'max_amount' => 'required',
            'min_amount' => 'required',
            'product_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $seller = Seller::where('access_token', $access_token)->first();
        $copon = Copon::find($id);
        if (!$copon) {
            return response()->json(['message' => 'Copon not found'], 404);
        }
        if ($seller->id == $copon->seller_id) {
            $copon->update([
            'copon_code'=>$request->copon_code,
            'rate_of_discount'=>$request->rate_of_discount,
            'max_amount'=>$request->max_amount,
            'min_amount'=>$request->min_amount,
            'product_id'=>$request->product_id,
            ]);
            $updatedCopon = Copon::find($id);
            $data = [
                'id'=>$updatedCopon->id,
                'copon_code'=>$updatedCopon->copon_code,
                'rate_of_discount'=>$updatedCopon->rate_of_discount,
                'max_amount'=>$updatedCopon->max_amount,
                'min_amount'=>$updatedCopon->min_amount,
                'product' => $updatedCopon->product->name,
                'seller' => $updatedCopon->seller->name,
                'created_at'=>$updatedCopon->created_at,
                'updated_at'=>$updatedCopon->updated_at,
                ];
            return response()->json($data);
        } else {
            return response()->json(['message' => 'You do not have access to update this Copon'], 403);
        }
    }

    public function delete(Request $request,$id){
        $access_token = $request->bearerToken();
        $seller = Seller::where('access_token', $access_token)->first();
        if (!$seller) {
            return response()->json(['message' => 'Seller not found'], 404);
        }
        $copon = Copon::find($id);
        if (!$copon) {
            return response()->json(['message' => 'Copon not found'], 404);
        }
        if ($seller->id == $copon->seller_id) {
            $copon->delete();
            return response()->json(['message' => 'Copon deleted successfully']);
        } else {
            return response()->json(['message' => 'You do not have access to delete this Copon'], 403);
        }
    }

}
