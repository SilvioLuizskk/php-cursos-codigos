<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * GET /api/products
     * Listar produtos com filtros e paginação
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
            'category_id' => 'integer|exists:categories,id',
            'search' => 'string|max:255',
            'min_price' => 'numeric|min:0',
            'max_price' => 'numeric|min:0',
            'rating' => 'numeric|min:0|max:5',
            'sort_by' => 'in:created_at,price,rating,name,popularity',
            'sort_order' => 'in:asc,desc',
            'featured' => 'boolean',
            'in_stock' => 'boolean',
            'tags' => 'string|max:255',
        ]);

        // Definir padrões
        $filters = array_merge([
            'page' => 1,
            'per_page' => 20,
            'sort_by' => 'created_at',
            'sort_order' => 'desc',
        ], $filters);

        try {
            $products = $this->productService->getFiltered($filters);

            return ProductResource::collection($products)->additional([
                'meta' => [
                    'total_products' => $products->total(),
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ],
                'filters_applied' => array_filter($filters, function($value) {
                    return !is_null($value) && $value !== '';
                }),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch products', [
                'error' => $e->getMessage(),
                'filters' => $filters,
            ]);

            return response()->json([
                'message' => 'Erro ao buscar produtos',
            ], 500);
        }
    }

    /**
     * GET /api/products/{id}
     * Obter detalhes de um produto
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load('category', 'reviews'));
    }

    /**
     * GET /api/products/featured
     * Obter produtos em destaque
     */
    public function featured(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $products = $this->productService->getFeatured();
        return ProductResource::collection($products);
    }

    /**
     * POST /api/products
     * Criar novo produto (Admin)
     */
    public function store(StoreProductRequest $request): ProductResource
    {
        $product = $this->productService->create($request->validated());
        return new ProductResource($product);
    }

    /**
     * PUT /api/products/{id}
     * Atualizar produto (Admin)
     */
    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product = $this->productService->update($product, $request->validated());
        return new ProductResource($product);
    }

    /**
     * DELETE /api/products/{id}
     * Deletar produto (Admin)
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->productService->delete($product);
        return response()->json(['message' => 'Produto deletado com sucesso']);
    }
}
