<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;

class HomeController extends Controller
{
    /**
     * Dados para a página inicial
     */
    public function index(): JsonResponse
    {
        // Produtos em destaque
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Categorias principais
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        // Estatísticas gerais (se usuário estiver logado)
        $stats = null;
        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
            $stats = [
                'total_orders' => Order::where('user_id', $user->id)->count(),
                'pending_orders' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
                'cart_items' => Cart::where('user_id', $user->id)->sum('quantity'),
            ];
        }

        return response()->json([
            'featured_products' => $featuredProducts,
            'categories' => $categories,
            'stats' => $stats,
            'banners' => $this->getActiveBanners(),
        ]);
    }

    /**
     * Buscar banners ativos (placeholder)
     */
    private function getActiveBanners(): array
    {
        $json = Storage::disk('local')->exists('home_banners.json') ? Storage::disk('local')->get('home_banners.json') : null;
        if ($json) {
            $banners = json_decode($json, true);
            if (is_array($banners)) {
                return $banners;
            }
        }
        // Retorno padrão se não houver arquivo
        return [
            [
                'id' => 1,
                'title' => 'Oferta Especial',
                'subtitle' => 'Até 50% off em produtos selecionados',
                'image' => '/banners/offer.jpg',
                'link' => '/produtos?offer=true',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'title' => 'Frete Grátis',
                'subtitle' => 'Em compras acima de R$ 150',
                'image' => '/banners/shipping.jpg',
                'link' => '/produtos',
                'is_active' => true,
            ],
        ];
    }

    // --- CRUD BANNER ---
    public function updateBanner(Request $request): JsonResponse
    {
        // Exemplo: salvar banners em arquivo JSON (pode ser substituído por model futuramente)
        $banners = $request->input('banners', []);
        Storage::disk('local')->put('home_banners.json', json_encode($banners));
        return response()->json(['message' => 'Banners atualizados com sucesso', 'banners' => $banners]);
    }

    public function deleteBanner($id): JsonResponse
    {
    $json = Storage::disk('local')->exists('home_banners.json') ? Storage::disk('local')->get('home_banners.json') : '[]';
    $banners = json_decode($json, true);
        $banners = array_filter($banners, fn($b) => $b['id'] != $id);
        Storage::disk('local')->put('home_banners.json', json_encode(array_values($banners)));
        return response()->json(['message' => 'Banner removido', 'banners' => array_values($banners)]);
    }

    // --- CRUD CATEGORIA EM DESTAQUE ---
    public function updateFeaturedCategory(Request $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->is_active = $request->input('is_active', true);
        $category->sort_order = $request->input('sort_order', 0);
        $category->save();
        return response()->json(['message' => 'Categoria atualizada', 'category' => $category]);
    }

    public function deleteFeaturedCategory($id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->is_active = false;
        $category->save();
        return response()->json(['message' => 'Categoria removida da home', 'category' => $category]);
    }

    // --- CRUD PRODUTO EM DESTAQUE ---
    public function updateFeaturedProduct(Request $request, $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->is_featured = $request->input('is_featured', true);
        $product->save();
        return response()->json(['message' => 'Produto atualizado', 'product' => $product]);
    }

    public function deleteFeaturedProduct($id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->is_featured = false;
        $product->save();
        return response()->json(['message' => 'Produto removido dos destaques', 'product' => $product]);
    }

    /**
     * Inscrever na newsletter
     */
    public function subscribeNewsletter(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email',
            'accept_terms' => 'required|accepted',
        ]);

        // Por enquanto, apenas retorna sucesso
        // Em produção, salvaria no banco de dados
        return response()->json([
            'message' => 'Inscrição realizada com sucesso! Verifique seu e-mail para confirmar.',
            'success' => true
        ]);
    }
}
