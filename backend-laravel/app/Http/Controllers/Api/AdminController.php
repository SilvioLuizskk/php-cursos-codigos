<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Page;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ==================== DASHBOARD ====================

    public function getDashboardStats()
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'total_products' => Product::count(),
                'total_orders' => Order::count(),
                'total_revenue' => Order::where('status', 'delivered')->sum('total'),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'recent_orders' => Order::with('user')->latest()->take(5)->get(),
                'low_stock_products' => Product::where('stock_quantity', '<=', 10)->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar estatísticas do dashboard'], 500);
        }
    }

    public function getDashboardCharts()
    {
        try {
            // Vendas por mês (últimos 12 meses)
            $salesByMonth = Order::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders_count')
            )
            ->where('status', 'delivered')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            // Top produtos vendidos
            $topProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', 'delivered')
                ->groupBy('products.id')
                ->orderBy('total_sold', 'desc')
                ->take(10)
                ->get();

            return response()->json([
                'sales_by_month' => $salesByMonth,
                'top_products' => $topProducts,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar dados dos gráficos'], 500);
        }
    }

    // ==================== PRODUTOS ====================

    public function getProducts(Request $request)
    {
        try {
            $query = Product::with('category');

            // Filtros
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $products = $query->paginate($request->get('per_page', 20));

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar produtos'], 500);
        }
    }

    public function createProduct(Request $request)
    {
        try {
            Log::info('Tentando criar produto', $request->all());

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'stock' => 'required|integer|min:0',
                'sku' => 'required|string|unique:products',
                'status' => 'required|in:active,inactive,draft',
                'images' => 'nullable|array',
                // Accept absolute URLs or storage paths for images
                'images.*' => ['nullable', 'string', 'regex:/^(https?:\\/\\/.+|\\/storage\\/.+)$/'],
            ]);

            Log::info('Dados validados', $validated);

            $product = Product::create($validated);

            Log::info('Produto criado', $product->toArray());

            return response()->json($product, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação', $e->errors());
            return response()->json(['error' => 'Dados inválidos', 'details' => $e->errors()], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle DB constraint violations (e.g., duplicate slug)
            Log::error('DB error ao criar produto', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao criar produto: conflito de integridade (slug/unique)'], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao criar produto', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Erro ao criar produto: ' . $e->getMessage()], 500);
        }
    }

    public function updateProduct(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric|min:0',
                'category_id' => 'sometimes|required|exists:categories,id',
                'stock' => 'sometimes|required|integer|min:0',
                'sku' => 'sometimes|required|string|unique:products,sku,' . $product->id,
                'status' => 'sometimes|required|in:active,inactive,draft',
                'images' => 'nullable|array',
                // Accept absolute URLs or storage paths for images
                'images.*' => ['nullable', 'string', 'regex:/^(https?:\\/\\/.+|\\/storage\\/.+)$/'],
            ]);

            $product->update($validated);

            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar produto'], 500);
        }
    }

    public function deleteProduct(Product $product)
    {
        try {
            $product->delete();
            return response()->json(['message' => 'Produto deletado com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar produto'], 500);
        }
    }

    public function bulkUpdateProducts(Request $request)
    {
        try {
            $validated = $request->validate([
                'products' => 'required|array',
                'products.*.id' => 'required|exists:products,id',
                'action' => 'required|in:update_status,update_price,delete',
                'value' => 'required_unless:action,delete',
            ]);

            DB::beginTransaction();

            foreach ($request->products as $productData) {
                $product = Product::find($productData['id']);

                switch ($request->action) {
                    case 'update_status':
                        $product->update(['status' => $request->value]);
                        break;
                    case 'update_price':
                        $product->update(['price' => $request->value]);
                        break;
                    case 'delete':
                        $product->delete();
                        break;
                }
            }

            DB::commit();

            return response()->json(['message' => 'Produtos atualizados com sucesso']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Erro ao atualizar produtos em lote'], 500);
        }
    }

    // ==================== CATEGORIAS ====================

    public function getCategories()
    {
        try {
            $categories = Category::all();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar categorias'], 500);
        }
    }

    public function createCategory(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                // Accept either an absolute URL (http(s)://...) or a storage path like '/storage/...'
                'image' => ['nullable', 'string', 'regex:/^(https?:\\/\\/.+|\\/storage\\/.+)$/'],
                'active' => 'required|boolean',
            ]);

            $category = Category::create($validated);

            return response()->json($category, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('DB error ao criar categoria', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao criar categoria: conflito de integridade (slug/unique)'], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar categoria'], 500);
        }
    }

    public function updateCategory(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                // Accept either an absolute URL or a storage path
                'image' => ['nullable', 'string', 'regex:/^(https?:\\/\\/.+|\\/storage\\/.+)$/'],
                'status' => 'sometimes|required|in:active,inactive',
            ]);

            $category->update($validated);

            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar categoria'], 500);
        }
    }

    public function deleteCategory(Category $category)
    {
        try {
            $category->delete();
            return response()->json(['message' => 'Categoria deletada com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar categoria'], 500);
        }
    }

    // ==================== BANNERS ====================

    public function getBanners()
    {
        try {
            $banners = Banner::orderBy('order')->get();
            return response()->json($banners);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar banners'], 500);
        }
    }

    public function createBanner(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                // Accept absolute URL or storage path
                'image' => ['required', 'string', 'regex:/^(https?:\\/\\/.+|\\/storage\\/.+)$/'],
                'link' => 'nullable|url',
                'position' => 'required|in:hero,sidebar,carousel',
                'status' => 'required|in:active,inactive',
                'order' => 'required|integer|min:0',
            ]);

            $banner = Banner::create($validated);

            return response()->json($banner, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar banner'], 500);
        }
    }

    public function updateBanner(Request $request, Banner $banner)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'image' => ['sometimes', 'required', 'string', 'regex:/^(https?:\\/\\/.+|\\/storage\\/.+)$/'],
                'link' => 'nullable|url',
                'position' => 'sometimes|required|in:hero,sidebar,carousel',
                'status' => 'sometimes|required|in:active,inactive',
                'order' => 'sometimes|required|integer|min:0',
            ]);

            $banner->update($validated);

            return response()->json($banner);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar banner'], 500);
        }
    }

    public function deleteBanner(Banner $banner)
    {
        try {
            $banner->delete();
            return response()->json(['message' => 'Banner deletado com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar banner'], 500);
        }
    }

    // ==================== PÁGINAS ====================

    public function getPages()
    {
        try {
            $pages = Page::all();
            return response()->json($pages);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar páginas'], 500);
        }
    }

    public function createPage(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|unique:pages',
                'content' => 'required|string',
                'status' => 'required|in:published,draft',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
            ]);

            $page = Page::create($validated);

            return response()->json($page, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar página'], 500);
        }
    }

    public function updatePage(Request $request, Page $page)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'slug' => 'sometimes|required|string|unique:pages,slug,' . $page->id,
                'content' => 'sometimes|required|string',
                'status' => 'sometimes|required|in:published,draft',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
            ]);

            $page->update($validated);

            return response()->json($page);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar página'], 500);
        }
    }

    public function deletePage(Page $page)
    {
        try {
            $page->delete();
            return response()->json(['message' => 'Página deletada com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar página'], 500);
        }
    }

    // ==================== CONFIGURAÇÕES ====================

    public function getSettings()
    {
        try {
            $settings = Setting::all()->pluck('value', 'key')->toArray();

            // Decode JSON fields
            $jsonFields = ['payment_methods'];
            foreach ($jsonFields as $field) {
                if (isset($settings[$field])) {
                    $settings[$field] = json_decode($settings[$field], true);
                }
            }

            return response()->json($settings);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar configurações'], 500);
        }
    }

    public function updateSettings(Request $request)
    {
        try {
            $validated = $request->validate([
                'store_name' => 'nullable|string|max:255',
                'contact_email' => 'nullable|email',
                'phone' => 'nullable|string|max:20',
                'currency' => 'nullable|string|size:3',
                'free_shipping_threshold' => 'nullable|numeric|min:0',
                'default_shipping_rate' => 'nullable|numeric|min:0',
                'enable_credit_card' => 'nullable|boolean',
                'enable_pix' => 'nullable|boolean',
                'enable_boleto' => 'nullable|boolean',
                'smtp_host' => 'nullable|string|max:255',
                'smtp_port' => 'nullable|integer|min:1|max:65535',
                'smtp_username' => 'nullable|string|max:255',
                'smtp_password' => 'nullable|string|max:255',
                'max_login_attempts' => 'nullable|integer|min:1|max:10',
                'lockout_duration' => 'nullable|integer|min:1',
                // Novos campos
                'logo_url' => 'nullable|string|max:500',
                'primary_color' => 'nullable|string|max:7',
                'secondary_color' => 'nullable|string|max:7',
                'meta_description' => 'nullable|string|max:160',
                'facebook_url' => 'nullable|string|max:500',
                'instagram_url' => 'nullable|string|max:500',
                'whatsapp' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'default_delivery_days' => 'nullable|integer|min:1',
                'enable_pickup' => 'nullable|boolean',
                // Simplificar validação de payment_methods
                'payment_methods' => 'nullable',
            ]);

            foreach ($validated as $key => $value) {
                // Handle nested arrays like payment_methods
                if (is_array($value)) {
                    Setting::updateOrCreate(
                        ['key' => $key],
                        ['value' => json_encode($value)]
                    );
                } else {
                    Setting::updateOrCreate(
                        ['key' => $key],
                        ['value' => $value]
                    );
                }
            }

            return response()->json(['message' => 'Configurações atualizadas com sucesso']);
        } catch (\Exception $e) {
            Log::error('Erro no updateSettings: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Erro ao atualizar configurações: ' . $e->getMessage()], 500);
        }
    }

    // ==================== PEDIDOS ====================

    public function getOrders(Request $request)
    {
        try {
            $query = Order::with('user');

            // Filtros
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->has('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            if ($request->has('order_id')) {
                $query->where('id', $request->order_id);
            }

            $orders = $query->paginate($request->get('per_page', 20));

            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar pedidos'], 500);
        }
    }

    public function getOrder(Order $order)
    {
        try {
            $order->load('user', 'items.product');
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar pedido'], 500);
        }
    }

    public function updateOrder(Request $request, Order $order)
    {
        try {
            $validated = $request->validate([
                'status' => 'sometimes|required|in:pending,processing,shipped,delivered,cancelled',
                'notes' => 'nullable|string',
            ]);

            $order->update($validated);

            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar pedido'], 500);
        }
    }

    public function deleteOrder(Order $order)
    {
        try {
            $order->delete();
            return response()->json(['message' => 'Pedido deletado com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar pedido'], 500);
        }
    }

    // ==================== MÉTRICAS ====================

    public function getMetrics(Request $request)
    {
        try {
            $startDate = $request->get('start_date', Carbon::now()->subDays(30)->toDateString());
            $endDate = $request->get('end_date', Carbon::now()->toDateString());

            $metrics = [
                'totalUsers' => User::count(),
                'totalOrders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
                'totalRevenue' => Order::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'delivered')
                    ->sum('total'),
                'totalProductsSold' => DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->where('orders.status', 'delivered')
                    ->sum('quantity'),
                'topProducts' => Product::select('products.*', DB::raw('SUM(order_items.quantity) as sales'))
                    ->join('order_items', 'products.id', '=', 'order_items.product_id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->where('orders.status', 'delivered')
                    ->groupBy('products.id')
                    ->orderBy('sales', 'desc')
                    ->take(10)
                    ->get()
                    ->map(function ($product) {
                        return [
                            'name' => $product->name,
                            'sales' => (int) $product->sales,
                            'revenue' => $product->price * $product->sales,
                        ];
                    }),
                'orderStatuses' => Order::select('status', DB::raw('COUNT(*) as count'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('status')
                    ->get()
                    ->map(function ($status) use ($startDate, $endDate) {
                        $total = Order::whereBetween('created_at', [$startDate, $endDate])->count();
                        return [
                            'status' => $status->status,
                            'count' => (int) $status->count,
                            'percentage' => $total > 0 ? round(($status->count / $total) * 100, 1) : 0,
                        ];
                    }),
                'revenueByCategory' => Category::select('categories.name as category',
                    DB::raw('SUM(order_items.quantity * order_items.price) as revenue'))
                    ->join('products', 'categories.id', '=', 'products.category_id')
                    ->join('order_items', 'products.id', '=', 'order_items.product_id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->where('orders.status', 'delivered')
                    ->groupBy('categories.id', 'categories.name')
                    ->orderBy('revenue', 'desc')
                    ->get()
                    ->map(function ($category) use ($startDate, $endDate) {
                        $total = Order::whereBetween('created_at', [$startDate, $endDate])
                            ->where('status', 'delivered')
                            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                            ->sum(DB::raw('order_items.quantity * order_items.price'));
                        return [
                            'category' => $category->category,
                            'revenue' => (float) $category->revenue,
                            'percentage' => $total > 0 ? round(($category->revenue / $total) * 100, 1) : 0,
                        ];
                    }),
            ];

            return response()->json($metrics);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar métricas'], 500);
        }
    }

    public function exportMetrics(Request $request)
    {
        try {
            // Implementar exportação CSV/Excel aqui
            // Por enquanto retorna uma resposta simples
            return response()->json(['message' => 'Funcionalidade de exportação será implementada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao exportar métricas'], 500);
        }
    }

    // ==================== USUÁRIOS ====================

    public function getUsers(Request $request)
    {
        try {
            $query = User::query();

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
            }

            $users = $query->paginate($request->get('per_page', 20));

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar usuários'], 500);
        }
    }

    public function createUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|in:user,admin',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'],
            ]);

            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar usuário'], 500);
        }
    }

    public function updateUser(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'role' => 'sometimes|required|in:user,admin',
                'password' => 'nullable|string|min:8',
            ]);

            if (isset($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            }

            $user->update($validated);

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar usuário'], 500);
        }
    }

    public function deleteUser(User $user)
    {
        try {
            $user->delete();
            return response()->json(['message' => 'Usuário deletado com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar usuário'], 500);
        }
    }

    public function toggleUserStatus(User $user)
    {
        try {
            // Implementar toggle de status (ativo/inativo)
            // Por enquanto apenas retorna sucesso
            return response()->json(['message' => 'Status do usuário alterado com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao alterar status do usuário'], 500);
        }
    }
}
