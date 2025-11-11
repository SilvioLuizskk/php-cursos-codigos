<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * GET /api/products/{productId}/reviews
     * Listar reviews de um produto
     */
    public function index(Request $request, int $productId): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $reviews = $this->reviewService->getProductReviews($productId, $request->all());
        return ReviewResource::collection($reviews);
    }

    /**
     * POST /api/products/{productId}/reviews
     * Criar nova review para um produto
     */
    public function store(StoreReviewRequest $request, int $productId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }

        $data = array_merge($request->validated(), [
            'product_id' => $productId,
            'user_id' => $user->id,
        ]);

        try {
            $review = $this->reviewService->create($data);
            return new ReviewResource($review);
        } catch (\Exception $e) {
            Log::error('Failed to create review', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            return response()->json([
                'message' => 'Erro ao criar avaliação',
            ], 500);
        }
    }

    /**
     * GET /api/reviews/{id}
     * Obter review específica
     */
    public function show(Review $review): ReviewResource
    {
        return new ReviewResource($review);
    }

    /**
     * PUT /api/reviews/{id}
     * Atualizar review (apenas do próprio usuário)
     */
    public function update(Request $request, Review $review)
    {
        $user = Auth::user();
        if ($review->user_id !== $user->id) {
            return response()->json(['message' => 'Não autorizado'], 403);
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            $review = $this->reviewService->update($review, $data);
            return new ReviewResource($review);
        } catch (\Exception $e) {
            Log::error('Failed to update review', [
                'error' => $e->getMessage(),
                'review_id' => $review->id,
            ]);

            return response()->json([
                'message' => 'Erro ao atualizar avaliação',
            ], 500);
        }
    }

    /**
     * DELETE /api/reviews/{id}
     * Deletar review (apenas do próprio usuário ou admin)
     */
    public function destroy(Review $review): JsonResponse
    {
        $user = Auth::user();
        if ($review->user_id !== $user->id && !$user->is_admin) {
            return response()->json(['message' => 'Não autorizado'], 403);
        }

        try {
            $this->reviewService->delete($review);
            return response()->json(['message' => 'Avaliação deletada com sucesso']);
        } catch (\Exception $e) {
            Log::error('Failed to delete review', [
                'error' => $e->getMessage(),
                'review_id' => $review->id,
            ]);

            return response()->json([
                'message' => 'Erro ao deletar avaliação',
            ], 500);
        }
    }

    /**
     * POST /api/reviews/{id}/helpful
     * Marcar review como útil
     */
    public function markHelpful(Review $review): JsonResponse
    {
        try {
            $this->reviewService->markHelpful($review);
            return response()->json(['message' => 'Avaliação marcada como útil']);
        } catch (\Exception $e) {
            Log::error('Failed to mark review as helpful', [
                'error' => $e->getMessage(),
                'review_id' => $review->id,
            ]);

            return response()->json([
                'message' => 'Erro ao marcar avaliação como útil',
            ], 500);
        }
    }
}
