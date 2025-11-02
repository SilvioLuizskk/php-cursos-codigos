<?php

use App\Http\Controllers\EpisodesController; // ← Adicionar este import
use App\Http\Controllers\SeasonsController;  // ← Verificar se este também existe
use App\Http\Controllers\SeriesController;   // ← Verificar se este também existe
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Middleware\Autenticador;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;


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

Route::get('/', function () {
    return redirect('/series');
});
Route::middleware(Autenticador::class)->group(function () {
    Route::resource('/series', SeriesController::class)
        ->except(
            ['show']
        );
        Route::get('/series/{series}/seasons', [SeasonsController::class, 'index'])->name('seasons.index');
        Route::get('/seasons/{season}/episodes', [EpisodesController::class, 'index'])->name('episodes.index');
        Route::post('/seasons/{season}/episodes', [EpisodesController::class, 'update'])->name('episodes.update');
});



Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [UsersController::class, 'create'])->name('users.create');
Route::post('/register', [UsersController::class, 'store'])->name('users.store');
