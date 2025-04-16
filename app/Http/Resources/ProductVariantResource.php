<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'size' => new SizeResource($this->whenLoaded('size')),
            'color' => new ColorResource($this->whenLoaded('color')),
            'price' => $this->price,
            'discount_percent' => $this->discount_percent,
            'discounted_price' => $this->discounted_price,
            'stock_quantity' => $this->stock_quantity,
            'sold' => $this->sold,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'main_image' => new ImageResource($this->whenLoaded('mainImage')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
