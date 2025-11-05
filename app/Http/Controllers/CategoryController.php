<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->paginate(15);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active');

        $category = Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::with(['products' => function($q) {
            $q->active()->with(['primaryImage', 'user'])->latest();
        }])->findOrFail($id);

        $products = $category->products()->active()
            ->with(['primaryImage', 'user'])
            ->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active');
        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Não é possível remover uma categoria que possui produtos.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Categoria removida com sucesso!');
    }
}
