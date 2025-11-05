<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test product creation.
     */
    public function test_product_creation()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'stock_quantity' => 10,
            'sku' => 'TEST123',
            'active' => true,
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals(99.99, $product->price);
        $this->assertTrue($product->active);
    }

    /**
     * Test product relationships.
     */
    public function test_product_relationships()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(User::class, $product->user);
        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($user->id, $product->user->id);
        $this->assertEquals($category->id, $product->category->id);
    }

    /**
     * Test product scopes.
     */
    public function test_product_scopes()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $activeProduct = Product::factory()->create([
            'active' => true,
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $inactiveProduct = Product::factory()->create([
            'active' => false,
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $activeProducts = Product::active()->get();
        $this->assertCount(1, $activeProducts);
        $this->assertEquals($activeProduct->id, $activeProducts->first()->id);
    }

    /**
     * Test product is in stock.
     */
    public function test_product_is_in_stock()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $inStockProduct = Product::factory()->create([
            'stock_quantity' => 5,
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $outOfStockProduct = Product::factory()->create([
            'stock_quantity' => 0,
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $this->assertTrue($inStockProduct->isInStock());
        $this->assertFalse($outOfStockProduct->isInStock());
    }
}
