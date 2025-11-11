<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ReviewService
{
    /**
     * Obter reviews de um produto
     */
    public function getProductReviews(int $productId, array $filters = []): LengthAwarePaginator
    {
        $query = Review::where('product_id', $productId)
            ->with(['user:id,name,email'])
            ->approved()
            ->orderBy('created_at', 'desc');

        $perPage = $filters['per_page'] ?? 10;
        return $query->paginate($perPage);
    }

    /**
     * Criar nova review
     */
    public function create(array $data): Review
    {
        // Verificar se o usuário já fez uma review para este produto
        $existingReview = Review::where('product_id', $data['product_id'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($existingReview) {
            throw new \Exception('Você já avaliou este produto.');
        }

        $review = Review::create(array_merge($data, [
            'status' => 'pending', // Reviews precisam ser aprovadas
        ]));

        // Atualizar rating do produto
        $this->updateProductRating($data['product_id']);

        Log::info('Review created', [
            'review_id' => $review->id,
            'product_id' => $data['product_id'],
            'user_id' => $data['user_id'],
            'rating' => $data['rating'],
        ]);

        return $review;
    }

    /**
     * Atualizar review
     */
    public function update(Review $review, array $data): Review
    {
        $review->update($data);

        // Atualizar rating do produto
        $this->updateProductRating($review->product_id);

        Log::info('Review updated', [
            'review_id' => $review->id,
            'rating' => $data['rating'] ?? $review->rating,
        ]);

        return $review;
    }

    /**
     * Deletar review
     */
    public function delete(Review $review): void
    {
        $productId = $review->product_id;

        $review->delete();

        // Atualizar rating do produto
        $this->updateProductRating($productId);

        Log::info('Review deleted', [
            'review_id' => $review->id,
            'product_id' => $productId,
        ]);
    }

    /**
     * Marcar review como útil
     */
    public function markHelpful(Review $review): void
    {
        $review->update(['helpful_count' => $review->helpful_count + 1]);
    }

    /**
     * Atualizar rating médio do produto baseado nas reviews aprovadas
     */
    private function updateProductRating(int $productId): void
    {
        $product = Product::find($productId);
        if (!$product) {
            return;
        }

        $avgRating = Review::where('product_id', $productId)
            ->approved()
            ->avg('rating');

        $reviewCount = Review::where('product_id', $productId)
            ->approved()
            ->count();

        $product->update([
            'rating' => $avgRating ?? 0,
            'review_count' => $reviewCount,
        ]);

        Log::info('Product rating updated', [
            'product_id' => $productId,
            'new_rating' => $avgRating,
            'review_count' => $reviewCount,
        ]);
    }

    /**
     * Obter estatísticas de reviews de um produto
     */
    public function getProductReviewStats(int $productId): array
    {
        $reviews = Review::where('product_id', $productId)
            ->approved()
            ->get();

        $stats = [
            'total_reviews' => $reviews->count(),
            'average_rating' => $reviews->avg('rating') ?? 0,
            'rating_distribution' => [
                5 => 0,
                4 => 0,
                3 => 0,
                2 => 0,
                1 => 0,
            ],
        ];

        foreach ($reviews as $review) {
            $stats['rating_distribution'][$review->rating]++;
        }

        return $stats;
    }
}
