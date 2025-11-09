<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'title',
        'comment',
        'verified_purchase',
        'helpful_count',
        'unhelpful_count',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'verified_purchase' => 'boolean',
    ];

    // Relacionamentos
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('verified_purchase', true);
    }

    public function scopeHighRated($query, $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }

    // Métodos
    public function approve(): void
    {
        $this->update(['status' => 'approved']);

        // Atualizar rating do produto
        $this->product->updateRating();
    }

    public function reject(string $reason = null): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    public function markAsHelpful(): void
    {
        $this->increment('helpful_count');
    }

    public function markAsUnhelpful(): void
    {
        $this->increment('unhelpful_count');
    }
}
