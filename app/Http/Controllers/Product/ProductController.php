<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProduct;
use App\Http\Requests\UpdateProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function index(){
        $products=Product::all();
        return view('products.index',compact($products));
    }

    public function show($id){
        $product=Product::findorfail($id);
        return view('products.show',compact($product));
    }

    public function create(){
        return view('products.create');
    }

    public function store(CreateProduct $createProduct){
        $seller_id = Auth::guard('seller')->user()->id;
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
        return view('products.show')->with('message', 'Product create successfully.');;
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
