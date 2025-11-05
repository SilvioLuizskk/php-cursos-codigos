<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductAPIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Rotas públicas da API
Route::get('products', [ProductAPIController::class, 'index']);
Route::get('products/{id}', [ProductAPIController::class, 'show']);
Route::get('categories', [ProductAPIController::class, 'categories']);
Route::get('search', [ProductAPIController::class, 'search']);

// Rotas protegidas da API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // CRUD de produtos via API
    Route::post('products', [ProductAPIController::class, 'store']);
    Route::put('products/{id}', [ProductAPIController::class, 'update']);
    Route::delete('products/{id}', [ProductAPIController::class, 'destroy']);
});
