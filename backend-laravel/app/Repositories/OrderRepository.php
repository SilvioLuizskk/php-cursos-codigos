<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository implements OrderRepositoryInterface
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function getByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->with(['items.product'])->get();
    }

    public function createItems($orderId, array $items)
    {
        $order = $this->find($orderId);
        foreach ($items as $item) {
            $order->items()->create($item);
        }

        return $order->items;
    }

    public function findByNumber($orderNumber)
    {
        return $this->model->where('order_number', $orderNumber)->first();
    }

    public function getUserOrders($userId, array $filters = [])
    {
        $query = $this->model->where('user_id', $userId)->with(['items.product']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($filters['per_page'] ?? 20);
    }

    public function cancel($id)
    {
        $order = $this->find($id);
        $order->cancel();
        return $order->fresh();
    }

    public function getOrderStatistics()
    {
        return [
            'total' => $this->model->count(),
            'total_value' => $this->model->sum('total_amount'),
        ];
    }

    public function update($id, array $data)
    {
        $order = $this->find($id);
        $order->update($data);
        return $order->fresh();
    }
}
