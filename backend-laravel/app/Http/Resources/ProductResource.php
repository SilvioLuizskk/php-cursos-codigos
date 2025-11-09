<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'featured' => $this->featured,
            'status' => $this->status,
            'in_stock' => $this->in_stock,
            'sku' => $this->sku,
            'weight' => $this->weight,
            'dimensions' => $this->dimensions,
            'images' => $this->images,
            'tags' => $this->tags,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'rating' => $this->rating,
            'rating_count' => $this->rating_count,
            'sales_count' => $this->sales_count,
            'view_count' => $this->view_count,
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
