<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface
{
    public function create(array $data);
    public function createItems($orderId, array $items);
    public function find($id);
    public function findByNumber($orderNumber);
    public function getUserOrders($userId);
    public function update($id, array $data);
    public function cancel($id);
    public function getOrderStatistics();
}
