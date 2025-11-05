<?php

namespace App\Repositories;

use App\Models\Product;
use App\Exceptions\ProductNotFoundException;
use App\Repositories\Contracts\ProductRepositoryInterface;

/**
 * Repositório simples para operações de leitura/escrita de Product.
 * Centraliza buscas e lança exceções de domínio quando necessário.
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Buscar produto por id com relacionamentos opcionais ou lançar ProductNotFoundException.
     *
     * @param  int|string  $id
     * @param  array  $with
     * @return Product
     *
     * @throws ProductNotFoundException
     */
    public function findOrFail($id, array $with = []): Product
    {
        $product = Product::with($with)->find($id);

        if (!$product) {
            throw new ProductNotFoundException("Produto com ID {$id} não foi encontrado.");
        }

        return $product;
    }

    /**
     * Buscar produto pertencente a um usuário específico ou lançar ProductNotFoundException.
     *
     * @param  int|string  $id
     * @param  int|string  $userId
     * @param  array  $with
     * @return Product
     *
     * @throws ProductNotFoundException
     */
    public function findForUserOrFail($id, $userId, array $with = []): Product
    {
        $product = Product::with($with)->where('user_id', $userId)->find($id);

        if (!$product) {
            throw new ProductNotFoundException("Produto com ID {$id} não encontrado para o usuário.");
        }

        return $product;
    }
}
