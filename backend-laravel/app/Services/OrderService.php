<?php

namespace App\Services;

use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function createOrder($userId, array $data)
    {
        // Obter itens do carrinho
        $cartItems = $this->cartRepository->getByUserId($userId);
        if ($cartItems->isEmpty()) {
            throw new \Exception('Carrinho vazio');
        }

        // Calcular totais
        $subtotal = 0;
        $orderItems = [];

        foreach ($cartItems as $item) {
            $product = $item->product;

            // Verificar estoque
            if ($product->stock < $item->quantity) {
                throw new \Exception("Produto {$product->name} sem estoque suficiente");
            }

            $subtotal += $product->price * $item->quantity;

            // Preparar item do pedido
            $orderItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $item->quantity,
                'price' => $product->price,
                'customization' => $item->customization,
                'subtotal' => $product->price * $item->quantity,
            ];

            // Decrementar estoque
            $this->productRepository->updateStock($product->id, -$item->quantity);
        }

        // Adicionar custos de envio e descontos
        $shippingCost = $this->calculateShipping($data['shipping_address']);
        $discountAmount = $this->calculateDiscount($data['coupon'] ?? null, $subtotal);
        $totalAmount = $subtotal + $shippingCost - $discountAmount;

        // Criar pedido
        $order = $this->orderRepository->create([
            'user_id' => $userId,
            'order_number' => $this->generateOrderNumber(),
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $data['payment_method'],
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'shipping_address' => $data['shipping_address'],
            'billing_address' => $data['billing_address'] ?? $data['shipping_address'],
            'notes' => $data['notes'] ?? null,
        ]);

        // Criar itens do pedido
        $this->orderRepository->createItems($order->id, $orderItems);

        // Limpar carrinho
        $this->cartRepository->clearByUserId($userId);

        // Disparar eventos
        // event(new OrderCreated($order));

        return $order;
    }

    public function getOrder($id)
    {
        return $this->orderRepository->find($id);
    }

    public function getUserOrders($userId)
    {
        return $this->orderRepository->getUserOrders($userId);
    }

    public function cancelOrder($orderId, $userId)
    {
        $order = $this->orderRepository->find($orderId);

        if ($order->user_id !== $userId) {
            throw new \Exception('Não autorizado');
        }

        if (!$order->canBeCancelled()) {
            throw new \Exception('Este pedido não pode ser cancelado');
        }

        return $this->orderRepository->cancel($orderId);
    }

    private function generateOrderNumber(): string
    {
        $prefix = date('Ymd');
        $random = strtoupper(Str::random(4));
        return $prefix . '-' . $random;
    }

    private function calculateShipping(array $address): float
    {
        // Implementar lógica de cálculo de frete
        return 15.00;
    }

    private function calculateDiscount(?string $couponCode, float $subtotal): float
    {
        if (!$couponCode) {
            return 0;
        }

        // Implementar lógica de cupons
        return 0;
    }
}
