<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,

            // Valores
            'subtotal' => $this->subtotal,
            'shipping_cost' => $this->shipping_cost,
            'discount_amount' => $this->discount_amount,
            'total_amount' => $this->total_amount,

            // Datas
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'shipped_at' => $this->shipped_at?->toISOString(),
            'delivered_at' => $this->delivered_at?->toISOString(),

            // Endereços
            'shipping_address' => $this->shipping_address,
            'billing_address' => $this->billing_address,

            // Informações de rastreamento
            'tracking_code' => $this->tracking_code,
            'tracking_url' => $this->tracking_url,

            // Observações
            'notes' => $this->notes,
            'admin_notes' => $this->when(
                $request->user()?->role === 'admin',
                $this->admin_notes
            ),

            // Relacionamentos
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'user' => new UserResource($this->whenLoaded('user')),

            // Status formatado
            'status_text' => $this->getStatusText(),
            'payment_status_text' => $this->getPaymentStatusText(),

            // Ações disponíveis
            'can_cancel' => $this->canBeCancelled(),
            'can_return' => $this->canBeReturned(),
        ];
    }
}
