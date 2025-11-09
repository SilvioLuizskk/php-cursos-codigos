<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'customization' => $this->customization,
            'subtotal' => $this->subtotal,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),

            // Informações do produto
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'slug' => $this->product->slug,
                'price' => $this->product->price,
                'discount_price' => $this->product->discount_price,
                'image' => $this->product->image_url,
                'in_stock' => $this->product->in_stock,
                'stock_quantity' => $this->product->stock,
            ],

            // Informações calculadas
            'unit_price' => $this->product->current_price,
            'total_price' => $this->quantity * $this->product->current_price,
            'is_available' => $this->product->is_active && $this->product->in_stock,
        ];
    }
}
