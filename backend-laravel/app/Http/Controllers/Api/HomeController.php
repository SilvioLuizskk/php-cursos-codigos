<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;

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
}
