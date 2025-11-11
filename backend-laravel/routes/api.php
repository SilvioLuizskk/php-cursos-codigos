<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ABDashboardController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'EstampariaPro API is running',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

Route::group([], function () {
    // Prometheus metrics endpoint (simple exporter)
    Route::get('/metrics', [\App\Http\Controllers\Monitoring\MetricsController::class, 'index']);
    // A/B testing endpoints
    Route::get('/ab/variant', [\App\Http\Controllers\ABTestController::class, 'getVariant']);
    Route::post('/ab/convert', [\App\Http\Controllers\ABTestController::class, 'recordConversion']);
    Route::get('/ab/stats', [\App\Http\Controllers\ABStatsController::class, 'stats']);
    Route::get('/ab/weights', [\App\Http\Controllers\ABAdminController::class, 'getWeights']);
    Route::post('/ab/weights', [\App\Http\Controllers\ABAdminController::class, 'setWeights']);

    // ==================== HOME ====================
    Route::get('/home', [HomeController::class, 'index']);

    // ==================== A/B DASHBOARD ====================
    Route::get('/ab-dashboard', [ABDashboardController::class, 'index']);

    // ==================== AUTENTICAÇÃO ====================
    Route::prefix('auth')->group(function () {
        // Rotas públicas
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);

        // Rotas protegidas
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('logout-all', [AuthController::class, 'logoutAll']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('user', [AuthController::class, 'user']);
        });
    });

    // ==================== PRODUTOS ====================
    Route::prefix('products')->group(function () {
        // Rotas públicas
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/featured', [ProductController::class, 'featured']);
        Route::get('/search', [ProductController::class, 'search']);
        Route::get('/{product:slug}', [ProductController::class, 'show']);

        // Reviews
        Route::get('/{productId}/reviews', [ReviewController::class, 'index']);
        Route::middleware('auth:sanctum')->post('/{productId}/reviews', [ReviewController::class, 'store']);

        // Rotas protegidas (admin) - TEMPORARIAMENTE SEM AUTENTICAÇÃO PARA TESTE
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
    });

    // ==================== CARRINHO ====================
    Route::middleware('auth:sanctum')->prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::get('/count', [CartController::class, 'count']);
        Route::post('/add', [CartController::class, 'store']);
        Route::put('/{cartItem}', [CartController::class, 'update']);
        Route::delete('/{cartItem}', [CartController::class, 'destroy']);
        Route::post('/apply-coupon', [CartController::class, 'applyCoupon']);
        Route::delete('/remove-coupon', [CartController::class, 'removeCoupon']);
    });

    // ==================== CHECKOUT ====================
    Route::middleware('auth:sanctum')->prefix('checkout')->group(function () {
        Route::post('/process', [CheckoutController::class, 'process']);
        Route::post('/validate', [CheckoutController::class, 'validateCheckout']);
    });

    // ==================== PEDIDOS ====================
    Route::middleware('auth:sanctum')->prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/stats', [OrderController::class, 'stats']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::patch('/{order}/cancel', [OrderController::class, 'cancel']);
        Route::get('/{order}/tracking', [OrderController::class, 'tracking']);
    });

    // ==================== REVIEWS ====================
    Route::middleware('auth:sanctum')->prefix('reviews')->group(function () {
        Route::get('/{review}', [ReviewController::class, 'show']);
        Route::put('/{review}', [ReviewController::class, 'update']);
        Route::delete('/{review}', [ReviewController::class, 'destroy']);
        Route::post('/{review}/helpful', [ReviewController::class, 'markHelpful']);
    });

    // ==================== PÁGINAS ====================
    Route::prefix('pages')->group(function () {
        // Rotas públicas
        Route::get('/', [PageController::class, 'index']);
        Route::get('/slug/{slug}', [PageController::class, 'showBySlug']);
        Route::get('/{page}', [PageController::class, 'show']);

        // Rotas protegidas (admin) - TEMPORARIAMENTE SEM AUTENTICAÇÃO PARA TESTE
        Route::post('/', [PageController::class, 'store']);
        Route::put('/{page}', [PageController::class, 'update']);
        Route::delete('/{page}', [PageController::class, 'destroy']);
    });

    // ==================== UPLOAD ====================
    Route::prefix('upload')->group(function () {
        // Rotas protegidas (admin) - TEMPORARIAMENTE SEM AUTENTICAÇÃO PARA TESTE
        Route::post('/image', [UploadController::class, 'uploadImage']);
        Route::delete('/image', [UploadController::class, 'deleteImage']);
        Route::get('/images', [UploadController::class, 'listImages']);
    });

    // ==================== HOME CONFIG (ADMIN) ====================
    // Configuração completa da home
    Route::put('/home-config', [HomeController::class, 'updateHomeConfig']);
    // Banner
    Route::put('/home-config/banner', [HomeController::class, 'updateBanner']);
    Route::delete('/home-config/banner/{id}', [HomeController::class, 'deleteBanner']);
    // Categoria em destaque
    Route::put('/home-config/category/{id}', [HomeController::class, 'updateFeaturedCategory']);
    Route::delete('/home-config/category/{id}', [HomeController::class, 'deleteFeaturedCategory']);
    // Produto em destaque
    Route::put('/home-config/product/{id}', [HomeController::class, 'updateFeaturedProduct']);
    Route::delete('/home-config/product/{id}', [HomeController::class, 'deleteFeaturedProduct']);
});

// ==================== NEWSLETTER ====================
Route::post('/newsletter/subscribe', [HomeController::class, 'subscribeNewsletter']);

// ==================== ADMIN ROUTES ====================
use App\Http\Controllers\Api\AdminController;

// TEMPORARIAMENTE SEM AUTENTICAÇÃO PARA TESTE
Route::prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard/stats', [AdminController::class, 'getDashboardStats']);
    Route::get('/dashboard/charts', [AdminController::class, 'getDashboardCharts']);

    // Produtos Admin
    Route::get('/products', [AdminController::class, 'getProducts']);
    Route::post('/products', [AdminController::class, 'createProduct']);
    Route::put('/products/{product}', [AdminController::class, 'updateProduct']);
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct']);
    Route::put('/products/bulk', [AdminController::class, 'bulkUpdateProducts']);

    // Categorias Admin
    Route::get('/categories', [AdminController::class, 'getCategories']);
    Route::post('/categories', [AdminController::class, 'createCategory']);
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory']);
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory']);

    // Banners Admin
    Route::get('/banners', [AdminController::class, 'getBanners']);
    Route::post('/banners', [AdminController::class, 'createBanner']);
    Route::put('/banners/{banner}', [AdminController::class, 'updateBanner']);
    Route::delete('/banners/{banner}', [AdminController::class, 'deleteBanner']);

    // Páginas Admin
    Route::get('/pages', [AdminController::class, 'getPages']);
    Route::post('/pages', [AdminController::class, 'createPage']);
    Route::put('/pages/{page}', [AdminController::class, 'updatePage']);
    Route::delete('/pages/{page}', [AdminController::class, 'deletePage']);

    // Configurações Admin
    Route::get('/settings', [AdminController::class, 'getSettings']);
    Route::put('/settings', [AdminController::class, 'updateSettings']);

    // Pedidos Admin
    Route::get('/orders', [AdminController::class, 'getOrders']);
    Route::get('/orders/{order}', [AdminController::class, 'getOrder']);
    Route::put('/orders/{order}', [AdminController::class, 'updateOrder']);
    Route::delete('/orders/{order}', [AdminController::class, 'deleteOrder']);

    // Métricas Admin
    Route::get('/metrics', [AdminController::class, 'getMetrics']);
    Route::get('/metrics/export', [AdminController::class, 'exportMetrics']);

    // Usuários Admin
    Route::get('/users', [AdminController::class, 'getUsers']);
    Route::post('/users', [AdminController::class, 'createUser']);
    Route::put('/users/{user}', [AdminController::class, 'updateUser']);
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser']);
    Route::put('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus']);
});
