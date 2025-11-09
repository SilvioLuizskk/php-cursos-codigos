<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CartRepository implements CartRepositoryInterface
{
    public function getByUserId($userId): Collection
    {
        return Cart::where('user_id', $userId)
                  ->with('product')
                  ->get();
    }

    public function findByUserAndId($userId, $cartItemId): ?Cart
    {
        return Cart::where('user_id', $userId)
                  ->where('id', $cartItemId)
                  ->first();
    }

    public function findByUserAndProduct($userId, $productId): ?Cart
    {
        return Cart::where('user_id', $userId)
                  ->where('product_id', $productId)
                  ->first();
    }

    public function create(array $data): Cart
    {
        return Cart::create($data);
    }

    public function update($id, array $data): bool
    {
        return Cart::where('id', $id)->update($data);
    }

    public function delete($id): bool
    {
        return Cart::destroy($id);
    }

    public function updateQuantity($id, $quantity): Cart
    {
        $cart = Cart::findOrFail($id);
        $cart->update(['quantity' => $quantity]);
        return $cart;
    }

    public function clearByUserId($userId): bool
    {
        return Cart::where('user_id', $userId)->delete();
    }

    public function getTotal($userId): float
    {
        return Cart::where('user_id', $userId)
                  ->with('product')
                  ->get()
                  ->sum(function ($item) {
                      return $item->product->price * $item->quantity;
                  });
    }
    public function getItemsCountByUserId($userId): int
    {
        return Cart::where('user_id', $userId)->count();
    }
}
