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
});
