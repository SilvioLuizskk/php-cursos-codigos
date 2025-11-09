<?php

namespace App\Providers;

use App\Repositories\CartRepository;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);

        // Services
        $this->app->bind(ProductService::class, function ($app) {
            return new ProductService(
                $app->make(ProductRepositoryInterface::class)
            );
        });

        $this->app->bind(CartService::class, function ($app) {
            return new CartService(
                $app->make(CartRepositoryInterface::class),
                $app->make(ProductRepositoryInterface::class)
            );
        });

        $this->app->bind(OrderService::class, function ($app) {
            return new OrderService(
                $app->make(OrderRepositoryInterface::class),
                $app->make(CartRepositoryInterface::class),
                $app->make(ProductRepositoryInterface::class)
            );
        });
    }
}
