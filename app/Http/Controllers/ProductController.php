<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Events\ProductCreated;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Exceptions\ProductNotFoundException;

class ProductController extends Controller
{
    private ProductRepositoryInterface $repo;

    public function __construct(ProductRepositoryInterface $repo)
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'user', 'primaryImage'])
            ->active();

        // Filtro por categoria
        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        // Busca por termo
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtro por estoque
        if ($request->filled('in_stock') && $request->in_stock) {
            $query->inStock();
        }

        // Ordenação
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');

        if (in_array($sortBy, ['name', 'price', 'created_at', 'stock_quantity'])) {
            $query->orderBy($sortBy, $sortDir);
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->get();

        return view('products.index', compact('products', 'categories', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\StoreProductRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['active'] = $request->has('active');

        $images = $request->hasFile('images') ? $request->file('images') : [];

        $product = $this->repo->add($validated, $images);

        // Disparar evento
        event(new ProductCreated($product));

        return redirect()->route('products.show', $product)
            ->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
    $product = $this->repo->findOrFail($id, ['category', 'user']);

        // carregar imagens com ordenação específica
        $product->load(['images' => function($q) {
            $q->ordered();
        }]);

        $relatedProducts = Product::active()
            ->byCategory($product->category_id)
            ->where('id', '!=', $product->id)
            ->with('primaryImage')
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
    $product = $this->repo->findForUserOrFail($id, Auth::id());
        $categories = Category::active()->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\UpdateProductRequest $request, $id)
    {
        // garante que o produto pertence ao usuário (lança se não)
        $this->repo->findForUserOrFail($id, Auth::id());

        $validated = $request->validated();
        $validated['active'] = $request->has('active');

        $images = $request->hasFile('images') ? $request->file('images') : [];

        $product = $this->repo->updateById($id, $validated, $images);

        return redirect()->route('products.show', $product)
            ->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    $product = $this->repo->findForUserOrFail($id, Auth::id());

        // Remove imagens físicas
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete(); // Soft delete

        return redirect()->route('products.index')
            ->with('success', 'Produto removido com sucesso!');
    }

    /**
     * Display user's products.
     */
    public function myProducts(Request $request)
    {
        $query = Product::where('user_id', Auth::id())
            ->with(['category', 'primaryImage'])
            ->withTrashed(); // Incluir produtos excluídos

        // Filtros
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('active', true)->whereNull('deleted_at');
                    break;
                case 'inactive':
                    $query->where('active', false)->whereNull('deleted_at');
                    break;
                case 'deleted':
                    $query->onlyTrashed();
                    break;
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        $categories = Category::active()->get();

        return view('products.my', compact('products', 'categories', 'request'));
    }
}
