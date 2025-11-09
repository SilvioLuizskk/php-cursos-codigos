<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'discount_price',
        'cost_price',
        'category_id',
        'sku',
        'barcode',
        'stock',
        'low_stock_alert',
        'rating',
        'review_count',
        'featured',
        'image',
        'images',
        'customization_options',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'seo_url',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'featured' => 'boolean',
        'images' => 'array',
        'customization_options' => 'array',
    ];

    protected $appends = ['discount_percentage', 'is_in_stock', 'average_rating'];

    /**
     * Escopo para produtos ativos
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Escopo para produtos em destaque
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    /**
     * Escopo para produtos em estoque
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Relação com categoria
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relação com reviews
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // Atributos Acessadores
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->discount_price) return 0;
        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->rating ?? 0, 1);
    }

    // Métodos
    public function decrementStock(int $quantity): void
    {
        $this->decrement('stock', $quantity);

        // TODO: Implementar evento de estoque baixo se necessário
        // if ($this->stock <= $this->low_stock_alert) {
        //     event(new \App\Events\LowStockAlert($this));
        // }
    }

    public function incrementStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }

    public function updateRating(): void
    {
        $avgRating = $this->reviews()->approved()->avg('rating');
        $reviewCount = $this->reviews()->approved()->count();

        $this->update([
            'rating' => $avgRating ?? 0,
            'review_count' => $reviewCount,
        ]);
    }

    public function getCustomizationPrice(array $customization): float
    {
        $additionalPrice = 0;

        if (isset($customization['size']) && isset($this->customization_options['sizes'])) {
            $size = collect($this->customization_options['sizes'])
                ->firstWhere('id', $customization['size']);
            if ($size) {
                $additionalPrice += $size['price_modifier'] ?? 0;
            }
        }

        if (isset($customization['text']) && isset($this->customization_options['text_fields'])) {
            $textField = collect($this->customization_options['text_fields'])
                ->firstWhere('id', $customization['text_field_id']);
            if ($textField) {
                $additionalPrice += $textField['price_modifier'] ?? 0;
            }
        }

        return $additionalPrice;
    }
}
