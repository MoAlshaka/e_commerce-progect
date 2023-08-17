<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Cate;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function index(Request $request){
        $access_token = $request->bearerToken();
        $seller=Seller::where('access_token',$access_token)->first();
        $products= Product::where('seller_id',$seller->id)->get();
        return response()->json(ProductResource::collection($products));

    }

    public function show(Request $request,$id){
        $access_token = $request->bearerToken();
        $seller=Seller::where('access_token',$access_token)->first();
        $product= new ProductResource(Product::find($id));
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    public function store(Request $request){
        $access_token = $request->bearerToken();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'desc' => 'required',
            'original_price' => 'required',
            'price_after_discount' => 'required',
            'stock' => 'required|integer',
            'tag' => 'required',
            'cate_id' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response($errors, 400);
        }

        $image=$request->image;
        $ext=$image->getClientOriginalExtension();
        $new_name=uniqid() . '.' . $ext;
        $image->move(public_path('images/products'),$new_name);
        $seller=Seller::where('access_token',$access_token)->first();
        $product=Product::create([
            'name'=>$request->name,
            'desc'=>$request->desc,
            'image'=>$new_name,
            'original_price'=>$request->original_price,
            'price_after_discount'=>$request->price_after_discount,
            'stock'=>$request->stock,
            'tag'=>$request->tag,
            'cate_id'=>$request->cate_id,
            'seller_id'=>$seller->id,
        ]);
        $data=[
            'id'=>$product->id,
            'name'=>$product->name,
            'description'=>$product->desc,
            'original_price'=>$product->original_price,
            'price_after_discount'=>$product->price_after_discount,
            'cate_id'=>$product->cate_id,
            'tag'=>$product->tag,
            'react'=>$product->react,
            'stock'=>$product->stock,
            'image'=>"public/images/products/$product->image",
            'author' => $product->seller->name,
            'created_at'=>$product->created_at,
            'updated_at'=>$product->updated_at,
        ];

        return response()->json($data,201);
    }

    public function update(Request $request,$id){
        $access_token = $request->bearerToken();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'desc' => 'required',
            'original_price' => 'required',
            'price_after_discount' => 'required',
            'stock' => 'required|integer',
            'tag' => 'required',
            'cate_id' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $seller = Seller::where('access_token', $access_token)->first();
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        if ($seller->id == $product->seller_id) {
            $image_name = $product->image;
            if ($request->hasFile('image')) {
                unlink(public_path("images/products/$image_name"));
                $image = $request->file('image');
                $ext = $image->getClientOriginalExtension();
                $image_name = uniqid() . '.' . $ext;
                $image->move(public_path('images/products'), $image_name);
            }
            $product->update([
                'name' => $request->name,
                'desc' => $request->desc,
                'image' => $image_name,
                'original_price' => $request->original_price,
                'price_after_discount' => $request->price_after_discount,
                'stock' => $request->stock,
                'tag' => $request->tag,
                'cate_id' => $request->cate_id,
            ]);
            $updatedProduct = Product::find($id);
            $data = [
                'id' => $updatedProduct->id,
                'name' => $updatedProduct->name,
                'description' => $updatedProduct->desc,
                'original_price' => $updatedProduct->original_price,
                'price_after_discount' => $updatedProduct->price_after_discount,
                'cate_id' => $updatedProduct->cate_id,
                'tag' => $updatedProduct->tag,
                'stock' => $updatedProduct->stock,
                'react' => $updatedProduct->react,
                'image' => "public/images/products/$updatedProduct->image",
                'author' => $updatedProduct->seller->name,
                'created_at' => $updatedProduct->created_at,
                'updated_at' => $updatedProduct->updated_at,
            ];
            return response()->json($data);
        } else {
            return response()->json(['message' => 'You do not have access to update this product'], 403);
        }
    }

    public function delete(Request $request,$id){
        $access_token = $request->bearerToken();
        $seller = Seller::where('access_token', $access_token)->first();
        if (!$seller) {
            return response()->json(['message' => 'Seller not found'], 404);
        }
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        if ($seller->id == $product->seller_id) {
            $imagePath = public_path("images/products/{$product->image}");

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully']);
        } else {
            return response()->json(['message' => 'You do not have access to delete this product'], 403);
        }
    }

}
