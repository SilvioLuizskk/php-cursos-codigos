<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ProductService
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Obter produtos filtrados com paginação
     */
    public function getFiltered(array $filters): LengthAwarePaginator
    {
        $query = Product::query();

        // Filtro por categoria
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Filtro por busca
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('tags', 'LIKE', "%{$search}%");
            });
        }

        // Filtros por preço
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Filtro por rating
        if (!empty($filters['rating'])) {
            $query->where('rating', '>=', $filters['rating']);
        }

        // Apenas produtos ativos
        $query->where('status', 'active');

        // Ordenação
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Paginação
        $perPage = $filters['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Obter produtos em destaque
     */
    public function getFeatured(): Collection
    {
        return Product::active()
            ->featured()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Criar novo produto
     */
    public function create(array $data): Product
    {
        // Gerar slug
        $data['slug'] = Str::slug($data['name']);

        // Gerar SKU se não fornecido
        if (empty($data['sku'])) {
            $data['sku'] = $this->generateSku($data['name']);
        }

        return $this->productRepository->create($data);
    }

    /**
     * Atualizar produto
     */
    public function update(Product $product, array $data): Product
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $this->productRepository->updateProduct($product, $data);
    }

    /**
     * Deletar produto (soft delete)
     */
    public function delete(Product $product): bool
    {
        return $this->productRepository->deleteProduct($product);
    }

    /**
     * Incrementar visualizações do produto
     */
    public function incrementViews(Product $product): void
    {
        $product->increment('view_count');
    }

    /**
     * Atualizar rating do produto baseado nas reviews
     */
    public function updateRating(Product $product): void
    {
        $reviews = $product->reviews()->where('is_approved', true)->get();

        if ($reviews->count() > 0) {
            $averageRating = $reviews->avg('rating');
            $product->update([
                'rating' => round($averageRating, 2),
                'rating_count' => $reviews->count()
            ]);
        }
    }

    /**
     * Verificar se produto está em estoque
     */
    public function checkStock(Product $product, int $quantity): bool
    {
        return $product->stock_quantity >= $quantity;
    }

    /**
     * Reduzir estoque do produto
     */
    public function reduceStock(Product $product, int $quantity): bool
    {
        if (!$this->checkStock($product, $quantity)) {
            return false;
        }

        $product->decrement('stock_quantity', $quantity);
        $product->increment('sales_count');

        return true;
    }

    public function searchProducts($filters)
    {
        return $this->productRepository->getFiltered($filters);
    }

    public function getFeaturedProducts()
    {
        return $this->productRepository->getFeatured();
    }

    private function generateSku(string $name): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
        $random = strtoupper(Str::random(5));
        return $prefix . '-' . $random;
    }
}
