<?php

namespace App\Repositories\Contracts;

use App\Models\Product;

interface ProductRepositoryInterface
{
    /**
     * @param  int|string  $id
     * @param  array  $with
     * @return Product
     */
    public function findOrFail($id, array $with = []): Product;

    /**
     * @param  int|string  $id
     * @param  int|string  $userId
     * @param  array  $with
     * @return Product
     */
    public function findForUserOrFail($id, $userId, array $with = []): Product;
}
