<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'subtotal' => $this->subtotal,
            'customization' => $this->customization,

            // Informações do produto
            'product' => [
                'id' => $this->product_id,
                'name' => $this->product_name,
                'slug' => $this->product?->slug,
                'image' => $this->product?->image_url,
                'sku' => $this->product?->sku,
            ],

            // Status do item
            'status' => $this->status ?? 'pending',
            'shipped_at' => $this->shipped_at?->toISOString(),
            'delivered_at' => $this->delivered_at?->toISOString(),

            // Informações de rastreamento específicas do item
            'tracking_code' => $this->tracking_code,
        ];
    }
}
