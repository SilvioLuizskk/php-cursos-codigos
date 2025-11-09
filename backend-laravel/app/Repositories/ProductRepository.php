<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return Product::active()->orderBy('created_at', 'desc')->get();
    }

    public function find($id)
    {
        return Product::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Product::where('slug', $slug)->firstOrFail();
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->find($id);
        $product->update($data);
        return $product;
    }

    /**
     * Atualizar produto (nova assinatura para compatibilidade)
     */
    public function updateProduct(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    public function delete($id)
    {
        return Product::destroy($id);
    }

    /**
     * Deletar produto (nova assinatura para compatibilidade)
     */
    public function deleteProduct(Product $product): bool
    {
        return $product->delete();
    }

    public function getFiltered(array $filters): LengthAwarePaginator
    {
        $query = Product::query();

        // Filtrar por categoria
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Filtrar por preço
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Filtrar por status
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        } else {
            $query->active();
        }

        // Busca por nome
        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
        }

        // Ordenação
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Paginação
        $perPage = $filters['per_page'] ?? 20;

        return $query->paginate($perPage);
    }

    public function getFeatured(): Collection
    {
        return Product::active()
            ->where('featured', true)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
    }

    public function search($query): Collection
    {
        return Product::active()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->take(10)
            ->get();
    }

    public function updateStock($id, $quantity): bool
    {
        $product = $this->find($id);

        if ($quantity < 0 && abs($quantity) > $product->stock) {
            return false;
        }

        return $product->increment('stock', $quantity);
    }
}
