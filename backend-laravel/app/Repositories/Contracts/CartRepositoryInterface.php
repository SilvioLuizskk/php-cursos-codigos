<?php

namespace App\Repositories\Contracts;

interface CartRepositoryInterface
{
    public function getByUserId($userId);
    public function findByUserAndProduct($userId, $productId);
    public function findByUserAndId($userId, $cartItemId);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function updateQuantity($id, $quantity);
    public function clearByUserId($userId);
    public function getTotal($userId);
    public function getItemsCountByUserId($userId);
}
