<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function createItems($orderId, array $items): Collection
    {
        $orderItems = collect($items)->map(function ($item) use ($orderId) {
            return OrderItem::create([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'customization' => $item['customization'] ?? null,
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        });

        return $orderItems;
    }

    public function find($id): Order
    {
        return Order::with(['items', 'user'])->findOrFail($id);
    }

    public function findByNumber($orderNumber): Order
    {
        return Order::with(['items', 'user'])
                   ->where('order_number', $orderNumber)
                   ->firstOrFail();
    }

    public function getUserOrders($userId): LengthAwarePaginator
    {
        return Order::with('items')
                   ->where('user_id', $userId)
                   ->orderBy('created_at', 'desc')
                   ->paginate(10);
    }

    public function update($id, array $data): bool
    {
        return Order::where('id', $id)->update($data);
    }

    public function cancel($id): bool
    {
        $order = $this->find($id);
        return $order->cancel();
    }

    public function getOrderStatistics(): array
    {
        $today = now()->startOfDay();
        $month = now()->startOfMonth();

        return [
            'total_orders' => Order::count(),
            'orders_today' => Order::where('created_at', '>=', $today)->count(),
            'orders_month' => Order::where('created_at', '>=', $month)->count(),
            'revenue_today' => Order::where('created_at', '>=', $today)
                                  ->where('payment_status', 'paid')
                                  ->sum('total_amount'),
            'revenue_month' => Order::where('created_at', '>=', $month)
                                  ->where('payment_status', 'paid')
                                  ->sum('total_amount'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
        ];
    }
}
