<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductImage;
use App\Exceptions\ProductNotFoundException;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Traits\SlugTrait;

/**
 * Repositório simples para operações de leitura/escrita de Product.
 * Centraliza buscas e lança exceções de domínio quando necessário.
 */
class ProductRepository implements ProductRepositoryInterface
{
    use SlugTrait;
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

    /**
     * {@inheritdoc}
     */
    public function add(array $data, array $images = []): Product
    {
        return DB::transaction(function () use ($data, $images) {
            // Normalizar slug se existir name
            if (isset($data['name']) && !isset($data['slug'])) {
                $data['slug'] = $this->generateSlug($data['name']);
            }

            $product = Product::create($data);

            // imagens opcionais (UploadedFile ou array já processado)
            foreach ($images as $index => $image) {
                if ($image instanceof UploadedFile) {
                    $filename = time() . '_' . $index . '_' . $image->getClientOriginalName();
                    $path = $image->storeAs('products', $filename, 'public');

                    $product->images()->create([
                        'filename' => $filename,
                        'original_name' => $image->getClientOriginalName(),
                        'path' => $path,
                        'size' => $image->getSize(),
                        'mime_type' => $image->getMimeType(),
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            return $product;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function updateById($id, array $data, array $images = []): Product
    {
        return DB::transaction(function () use ($id, $data, $images) {
            $product = $this->findOrFail($id);

            if (isset($data['name']) && !isset($data['slug'])) {
                $data['slug'] = $this->generateSlug($data['name']);
            }

            $product->update($data);

            // Upload novas imagens
            $currentImagesCount = $product->images()->count();

            foreach ($images as $index => $image) {
                if ($image instanceof UploadedFile) {
                    $filename = time() . '_' . ($currentImagesCount + $index) . '_' . $image->getClientOriginalName();
                    $path = $image->storeAs('products', $filename, 'public');

                    $product->images()->create([
                        'filename' => $filename,
                        'original_name' => $image->getClientOriginalName(),
                        'path' => $path,
                        'size' => $image->getSize(),
                        'mime_type' => $image->getMimeType(),
                        'is_primary' => $currentImagesCount === 0 && $index === 0,
                        'sort_order' => $currentImagesCount + $index,
                    ]);
                }
            }

            return $product;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function findByFilters(array $filters = [], int $perPage = 12, array $with = [])
    {
        $query = Product::with($with);

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $query->where('active', true)->whereNull('deleted_at');
            } elseif ($filters['status'] === 'inactive') {
                $query->where('active', false)->whereNull('deleted_at');
            } elseif ($filters['status'] === 'deleted') {
                $query->onlyTrashed();
            }
        }

        if (!empty($filters['price_min'])) {
            $query->where('price', '>=', $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $query->where('price', '<=', $filters['price_max']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%'.$filters['search'].'%')
                  ->orWhere('description', 'like', '%'.$filters['search'].'%')
                  ->orWhere('sku', 'like', '%'.$filters['search'].'%');
            });
        }

        if (!empty($filters['in_stock'])) {
            $query->where('stock_quantity', '>', 0);
        }

        $sortBy = $filters['sort'] ?? 'created_at';
        $sortDir = $filters['direction'] ?? 'desc';

        if (in_array($sortBy, ['name', 'price', 'created_at', 'stock_quantity'])) {
            $query->orderBy($sortBy, $sortDir);
        }

        return $query->paginate($perPage);
    }
}
