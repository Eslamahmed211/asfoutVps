<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class orderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        'reference' => $this->reference,
        'name' => $this->clientName,
        'phone' => $this->clientPhone,
        'city' => $this->city,
        'status' => $this->status,
        'details' => detailsResource::collection($this->whenLoaded('details')),
        'class' =>StatusClass($this->status),

    ];

    }
}
