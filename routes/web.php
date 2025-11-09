<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Página inicial - redirect para produtos
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Rotas de produtos (públicas e privadas)
Route::resource('products', ProductController::class);

// Rotas de categorias
Route::resource('categories', CategoryController::class);

// Rota para busca de produtos
Route::get('/search', [ProductController::class, 'index'])->name('products.search');

// Autenticação
Auth::routes();

// Dashboard (para usuários autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/my-products', [ProductController::class, 'myProducts'])->name('products.my');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
