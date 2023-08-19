<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'copon_code'=>$this->copon_code,
            'rate_of_discount'=>$this->rate_of_discount,
            'max_amount'=>$this->max_amount,
            'min_amount'=>$this->min_amount,
            'product'=>$this->product->name,
            'seller'=>$this->seller->name,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
        ];
    }
}
