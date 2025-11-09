<?php

namespace App\Services;

use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Str;

class OrderService
{
    private OrderRepositoryInterface $orderRepository;
    private CartRepositoryInterface $cartRepository;
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CartRepositoryInterface $cartRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

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
        $discountAmount = $this->calculateDiscount($data['coupon_code'] ?? null, $subtotal, $userId);
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

    public function getUserOrders($userId, array $filters = [])
    {
        return $this->orderRepository->getUserOrders($userId, $filters);
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

    public function getTrackingInfo($orderId)
    {
        $order = $this->orderRepository->find($orderId);
        return [
            'tracking_code' => $order->tracking_code,
            'tracking_url' => $order->tracking_url,
            'status' => $order->status,
            'shipped_at' => $order->shipped_at,
            'delivered_at' => $order->delivered_at,
        ];
    }

    public function getUserOrderStats($userId)
    {
        $orders = $this->orderRepository->getUserOrders($userId);
        return [
            'total_orders' => $orders->count(),
            'total_spent' => $orders->sum('total_amount'),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'completed_orders' => $orders->where('status', 'delivered')->count(),
        ];
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

    private function calculateDiscount(?string $couponCode, float $subtotal, int $userId): float
    {
        if (!$couponCode) {
            return 0;
        }

        $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
        if (!$coupon) {
            throw new \Exception('Cupom inválido');
        }

        $user = \App\Models\User::find($userId);
        if (!$coupon->canBeUsedBy($user, $subtotal)) {
            throw new \Exception('Cupom não pode ser utilizado');
        }

        $discount = $coupon->calculateDiscount($subtotal);

        // Incrementar uso do cupom
        $coupon->incrementUsage();

        return $discount;
    }
}
