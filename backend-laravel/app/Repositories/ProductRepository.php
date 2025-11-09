<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function add(array $data, array $images = []): Product
    {
        $product = Product::create($data);
        // Handle images if needed
        // Example: $product->images()->createMany($images);
        return $product;
    }

    public function findByFilters(array $filters = [], int $perPage = 12, array $with = [])
    {
        $query = Product::query();

        if (!empty($with)) {
            $query->with($with);
        }

        // Example filters
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function findForUserOrFail($id, $userId, array $with = []): Product
    {
        $query = Product::where('id', $id)->where('user_id', $userId);
        if (!empty($with)) {
            $query->with($with);
        }
        return $query->firstOrFail();
    }

    public function findOrFail($id, array $with = []): Product
    {
        $query = Product::where('id', $id);
        if (!empty($with)) {
            $query->with($with);
        }
        return $query->firstOrFail();
    }

    public function updateById($id, array $data, array $images = []): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        // Handle images if needed
        // Example: $product->images()->sync($images);
        return $product->fresh();
    }

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
