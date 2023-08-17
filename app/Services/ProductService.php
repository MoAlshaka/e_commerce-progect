<?php

namespace App\Http;

use App\Http\Requests\CreateProduct;
use App\Http\Requests\UpdateProduct;
use App\Models\Product;
use App\Models\Saller;
use Illuminate\Http\Request;

class ProductService{

    public function get_all_product(){

        return Product::all();
    }

    public function get_one_product(Request $request, $id){
        $saller=Saller::where('access_token',$request->access_token)->first();
        $product= product::with(['user:id,name','reacts'=> function($query) use ($saller){
            $query->select('product_user.product_id','product_user.user_id');
            $query->where('user_id',$saller->id);
         }])->find($id);
        if ( $product == null ) {
            return response()->json('There is no product found');
        }
            return response()->json(new productResource($product));

    }

    public function store(CreateProduct $createProduct,$access_token){
        $seller = Auth::guard('seller')->user()->id;
        $image=$createProduct->image;
        $ext=$image->getClientOriginalExtension();
        $new_name=uniqid() . '.' . $ext;
        $image->move(public_path('images/products'),$new_name);
        $product=Product::create([
        'name'=>$createProduct->name,
        'desc'=>$createProduct->desc,
        'image'=>$new_name,
        'original_price'=>$createProduct->original_price,
        'price_after_descout'=>$createProduct->price_after_descout,
        'stock'=>$createProduct->stock,
        'cate_id'=>$createProduct->cate,
        'saller_id'=>$createProduct->$seller_id,
    ]);
        return $product;
    }

    public function edit(){
        return view('products.create');
    }

    public function update(UpdateProduct $updateProduct,$id){

        $seller_id = Auth::guard('seller')->user()->id;
        $product=product::with('saller')->find($id);
        $image_name=$product->image;
        if($seller_id == $product->user_id){
            if($updateProduct->hasFile('image')){
                unlink(public_path("images/products/$image_name"));
                $image=$updateProduct->image;
                $ext=$image->getClientOriginalExtension();
                $image_name=uniqid() . '.' . $ext;
                $image->move(public_path('images/products'),$image_name);
            }

            $product->update([
                'name'=>$updateProduct->name,
                'desc'=>$updateProduct->desc,
                'image'=>$image_name,
                'original_price'=>$updateProduct->original_price,
                'price_after_descout'=>$updateProduct->price_after_descout,
                'stock'=>$updateProduct->stock,
                'cate_id'=>$updateProduct->cate,
            ]);
            return view('products.show')->with('message', 'Product update successfully.');
        }else {
            return redirect()->back()->with('message', 'you have not access to update.');
        }
    }
}
