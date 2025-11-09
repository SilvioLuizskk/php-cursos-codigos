<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_purchase_amount',
        'max_discount_amount',
        'max_uses',
        'used_count',
        'per_user_limit',
        'applicable_categories',
        'applicable_products',
        'excluded_products',
        'valid_from',
        'valid_until',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'applicable_categories' => 'array',
        'applicable_products' => 'array',
        'excluded_products' => 'array',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relacionamentos
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('valid_from', '<=', now())
                    ->where('valid_until', '>=', now());
    }

    public function scopeAvailable($query)
    {
        return $query->active()
                    ->where(function ($q) {
                        $q->whereNull('max_uses')
                          ->orWhereColumn('used_count', '<', 'max_uses');
                    });
    }

    // Métodos
    public function isValid(): bool
    {
        return $this->is_active
            && $this->valid_from <= now()
            && $this->valid_until >= now()
            && ($this->max_uses === null || $this->used_count < $this->max_uses);
    }

    public function canBeUsedBy(User $user, float $orderTotal): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        // Verificar valor mínimo
        if ($orderTotal < $this->min_purchase_amount) {
            return false;
        }

        // Verificar limite por usuário
        if ($this->per_user_limit > 0) {
            $userUsageCount = $user->orders()
                ->whereJsonContains('coupon_code', $this->code)
                ->count();
            
            if ($userUsageCount >= $this->per_user_limit) {
                return false;
            }
        }

        return true;
    }

    public function calculateDiscount(float $orderTotal): float
    {
        switch ($this->discount_type) {
            case 'percentage':
                $discount = $orderTotal * ($this->discount_value / 100);
                break;
            case 'fixed_amount':
                $discount = $this->discount_value;
                break;
            case 'free_shipping':
                return 0; // Handled separately
            default:
                return 0;
        }

        // Aplicar desconto máximo se definido
        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        return min($discount, $orderTotal);
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
}