<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class UserProductController extends Controller
{
    public function all_product(){
        $products=Product::all();
        if(!$products){
            return response()->json(['message' => 'Products not found'], 404);
        }
        return response()->json(ProductResource::collection($products));
    }

    public function one_product($id){
        $product=Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json(new ProductResource($product));

    }
}
