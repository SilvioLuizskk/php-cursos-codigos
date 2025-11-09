<?php

namespace App\Services;

use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CartService
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function getCartItems(int $userId): Collection
    {
        return $this->cartRepository->getByUserId($userId);
    }

    /**
     * Obter resumo do carrinho
     */
    public function getCartSummary(int $userId): array
    {
        $cartItems = $this->getCartItems($userId);

        $subtotal = 0;
        $totalItems = 0;
        $totalQuantity = 0;

        foreach ($cartItems as $item) {
            $itemTotal = $item->quantity * $item->product->current_price;
            $subtotal += $itemTotal;
            $totalItems++;
            $totalQuantity += $item->quantity;
        }

        // Calcular frete (frete grátis acima de R$ 100)
        $shippingCost = $subtotal >= 100 ? 0 : 15.00;
        $discountAmount = 0;

        $total = $subtotal + $shippingCost - $discountAmount;

        return [
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount_amount' => $discountAmount,
            'total' => $total,
            'total_items' => $totalItems,
            'total_quantity' => $totalQuantity,
        ];
    }

    public function addToCart(int $userId, int $productId, int $quantity = 1, ?array $customization = null)
    {
        // Verificar produto
        $product = $this->productRepository->find($productId);
        if (!$product || !$product->inStock()) {
            throw new \Exception('Produto não disponível');
        }

        // Verificar se já existe
        $existingItem = $this->cartRepository->findByUserAndProduct($userId, $productId);
        if ($existingItem) {
            return $this->updateQuantity($userId, $existingItem->id, $existingItem->quantity + $quantity);
        }

        // Criar novo item
        return $this->cartRepository->create([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'customization' => $customization,
        ]);
    }

    public function updateQuantity(int $userId, int $cartItemId, int $quantity)
    {
        $cartItem = $this->cartRepository->findByUserAndId($userId, $cartItemId);

        if (!$cartItem) {
            throw new \Exception('Item não encontrado');
        }

        if ($quantity <= 0) {
            return $this->cartRepository->delete($cartItemId);
        }

        // Verificar estoque
        $product = $this->productRepository->find($cartItem->product_id);
        if ($product->stock < $quantity) {
            throw new \Exception('Estoque insuficiente');
        }

        return $this->cartRepository->update($cartItemId, [
            'quantity' => $quantity,
        ]);
    }

    public function removeFromCart(int $userId, int $cartItemId)
    {
        $cartItem = $this->cartRepository->findByUserAndId($userId, $cartItemId);

        if (!$cartItem) {
            throw new \Exception('Item não encontrado');
        }

        return $this->cartRepository->delete($cartItemId);
    }

    public function clearCart(int $userId)
    {
        return $this->cartRepository->clearByUserId($userId);
    }

    public function getCartItemsCount(int $userId): int
    {
        return $this->cartRepository->getItemsCountByUserId($userId);
    }

    public function applyCoupon(int $userId, string $couponCode): array
    {
        // Implementação básica para aplicar cupom
        throw new \Exception('Funcionalidade de cupom em desenvolvimento');
    }

    public function removeCoupon(int $userId): bool
    {
        // Implementação básica para remover cupom
        throw new \Exception('Funcionalidade de cupom em desenvolvimento');
    }


}
