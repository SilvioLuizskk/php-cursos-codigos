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

    /**
     * Adiciona um novo produto (atributos e imagens opcionalmente).
     * Deve garantir atomicidade (transação) na implementação.
     *
     * @param array $data
     * @param array $images Array de UploadedFile ou caminhos
     * @return Product
     */
    public function add(array $data, array $images = []): Product;

    /**
     * Atualiza um produto existente por id em contexto de usuário (opcionalmente com imagens).
     * Deve usar transação internamente para consistência.
     *
     * @param int|string $id
     * @param array $data
     * @param array $images
     * @return Product
     */
    public function updateById($id, array $data, array $images = []): Product;

    /**
     * Busca produtos com filtros (category, price range, status etc.).
     * Retorna um paginator por padrão.
     *
     * @param array $filters
     * @param int $perPage
     * @param array $with
     * @return mixed
     */
    public function findByFilters(array $filters = [], int $perPage = 12, array $with = []);
}
