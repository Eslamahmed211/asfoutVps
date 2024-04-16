<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class detailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [

        "id" => $this->id,
        'discription' => $this->discription,
        "img" => path($this->product->firstImg->img) ,
        "qnt" => $this->qnt ,
        "total" => $this->qnt * ($this->price + $this->comissation) ,
        "comissation" => $this->qnt * ( $this->TotalComissation) ,
        "sku" => $this->variant_id != null ? $this->variant->sku : $this->product->sku ,


    ];
    }
}
