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
            'product_id' => $this->product_id,
            'size_id' => $this->size_id,
            'color_id' => $this->color_id,
            'price' => $this->price,
            'discount_percent' => $this->discount_percent,
            'discounted_price' => $this->discounted_price,
            'stock_quantity' => $this->stock_quantity,
            'sold' => $this->sold,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'product' => new ProductResource($this->whenLoaded('product')),
            'size' => new SizeResource($this->whenLoaded('size')),
            'color' => new ColorResource($this->whenLoaded('color')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'main_image' => new ImageResource($this->whenLoaded('mainImage')),
        ];
    }
}
