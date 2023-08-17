<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return[
            'id'=>$this->id,
            'name'=>$this->name,
            'description'=>$this->desc,
            'image'=>"public/images/products/$this->image",
            'original_price'=>$this->original_price,
            'price_after_discount'=>$this->price_after_discount,
            'cate_id'=>$this->cate->name,
            'tag'=>$this->tag,
            'react'=>$this->react,
            'stock'=>$this->stock,
            'auther'=>$this->seller->name,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
        ];
    }
}
