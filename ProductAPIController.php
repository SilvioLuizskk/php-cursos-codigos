<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Events\ProductCreated;

class ProductAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'user', 'primaryImage'])
            ->active();

        // Filtros
        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('in_stock') && $request->in_stock) {
            $query->inStock();
        }

        // Ordenação
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');

        if (in_array($sortBy, ['name', 'price', 'created_at', 'stock_quantity'])) {
            $query->orderBy($sortBy, $sortDir);
        }

        $products = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'active' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['active'] = $request->get('active', true);

        $product = Product::create($validated);
        $product->load(['category', 'user', 'images']);

        // Disparar evento
        event(new ProductCreated($product));

        return response()->json([
            'success' => true,
            'message' => 'Produto criado com sucesso!',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $product = Product::with(['category', 'user', 'images'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $product = Product::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'active' => 'boolean',
        ]);

        $product->update($validated);
        $product->load(['category', 'user', 'images']);

        return response()->json([
            'success' => true,
            'message' => 'Produto atualizado com sucesso!',
            'data' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $product = Product::where('user_id', auth()->id())->findOrFail($id);
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produto removido com sucesso!'
        ]);
    }

    /**
     * Get categories for dropdown
     */
    public function categories(): JsonResponse
    {
        $categories = Category::active()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Search products
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query deve ter pelo menos 2 caracteres'
            ], 400);
        }

        $products = Product::active()
            ->search($query)
            ->with(['category', 'primaryImage'])
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
