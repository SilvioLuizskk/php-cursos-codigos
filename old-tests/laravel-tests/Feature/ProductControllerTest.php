<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test listing products.
     */
    public function test_index_displays_products()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id, 'user_id' => $user->id]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    /**
     * Test creating a product requires authentication.
     */
    public function test_create_requires_authentication()
    {
        $response = $this->get(route('products.create'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test authenticated user can create product.
     */
    public function test_authenticated_user_can_create_product()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'stock_quantity' => 10,
            'sku' => 'TEST123',
            'category_id' => $category->id,
            'active' => true,
        ];

        $response = $this->actingAs($user)->post(route('products.store'), $productData);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', array_merge($productData, ['user_id' => $user->id]));
    }

    /**
     * Test product validation.
     */
    public function test_product_creation_validation()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('products.store'), []);

        $response->assertSessionHasErrors(['name', 'price', 'stock_quantity', 'sku', 'category_id']);
    }

    /**
     * Test showing product.
     */
    public function test_show_product()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'user_id' => $user->id]);

        $response = $this->get(route('products.show', $product));

        $response->assertStatus(200);
        $response->assertViewHas('product');
    }
}
