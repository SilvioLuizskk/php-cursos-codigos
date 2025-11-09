<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->middleware('auth:sanctum');
        $this->cartService = $cartService;
    }

    /**
     * GET /api/cart
     * Obter itens do carrinho do usuário
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $cartItems = $this->cartService->getCartItems($request->user()->id);
            $cartSummary = $this->cartService->getCartSummary($request->user()->id);

            return response()->json([
                'items' => CartResource::collection($cartItems),
                'summary' => $cartSummary,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch cart', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
            ]);

            return response()->json([
                'message' => 'Erro ao carregar carrinho',
            ], 500);
        }
    }

    /**
     * POST /api/cart/add
     * Adicionar produto ao carrinho
     */
    public function store(AddToCartRequest $request): JsonResponse
    {
        try {
            $cartItem = $this->cartService->addToCart(
                userId: $request->user()->id,
                productId: $request->product_id,
                quantity: $request->quantity ?? 1,
                customization: $request->customization
            );

            return response()->json([
                'message' => 'Produto adicionado ao carrinho',
                'cart_item' => new CartResource($cartItem),
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to add to cart', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * PUT /api/cart/{cartItem}
     * Atualizar quantidade do item no carrinho
     */
    public function update(UpdateCartRequest $request, int $cartItemId): JsonResponse
    {
        try {
            $cartItem = $this->cartService->updateQuantity(
                userId: $request->user()->id,
                cartItemId: $cartItemId,
                quantity: $request->quantity
            );

            return response()->json([
                'message' => 'Carrinho atualizado',
                'cart_item' => new CartResource($cartItem),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update cart item', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
                'cart_item_id' => $cartItemId,
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * DELETE /api/cart/{cartItem}
     * Remover item do carrinho
     */
    public function destroy(Request $request, int $cartItemId): JsonResponse
    {
        try {
            $this->cartService->removeFromCart(
                userId: $request->user()->id,
                cartItemId: $cartItemId
            );

            return response()->json([
                'message' => 'Item removido do carrinho',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to remove cart item', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
                'cart_item_id' => $cartItemId,
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * POST /api/cart/apply-coupon
     * Aplicar cupom de desconto
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        try {
            $result = $this->cartService->applyCoupon(
                userId: $request->user()->id,
                couponCode: $request->coupon_code
            );

            return response()->json([
                'message' => 'Cupom aplicado com sucesso',
                'discount' => $result,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to apply coupon', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
                'coupon_code' => $request->coupon_code,
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * DELETE /api/cart/remove-coupon
     * Remover cupom aplicado
     */
    public function removeCoupon(Request $request): JsonResponse
    {
        try {
            $this->cartService->removeCoupon($request->user()->id);

            return response()->json([
                'message' => 'Cupom removido',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to remove coupon', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
            ]);

            return response()->json([
                'message' => 'Erro ao remover cupom',
            ], 500);
        }
    }

    /**
     * GET /api/cart/count
     * Obter contagem de itens no carrinho
     */
    public function count(Request $request): JsonResponse
    {
        try {
            $count = $this->cartService->getCartItemsCount($request->user()->id);

            return response()->json([
                'count' => $count,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get cart count', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
            ]);

            return response()->json([
                'count' => 0,
            ]);
        }
    }
}
