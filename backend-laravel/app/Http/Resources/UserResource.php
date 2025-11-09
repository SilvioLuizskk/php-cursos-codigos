<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'last_login_at' => $this->last_login_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),

            // Estatísticas opcionais
            'orders_count' => $this->whenCounted('orders'),
            'wishlist_count' => $this->whenCounted('wishlist'),

            // Meta informações apenas para admin
            'last_login_ip' => $this->when(
                $request->user()?->role === 'admin',
                $this->last_login_ip
            ),
            'avatar' => $this->avatar,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date,
        ];
    }
}
