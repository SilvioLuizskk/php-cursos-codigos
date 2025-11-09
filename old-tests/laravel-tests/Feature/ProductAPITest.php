<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductAPITest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test API listing products.
     */
    public function test_api_can_list_products()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id, 'user_id' => $user->id]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'price',
                             'category',
                             'user'
                         ]
                     ],
                     'pagination'
                 ]);
    }

    /**
     * Test API can show single product.
     */
    public function test_api_can_show_product()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'user_id' => $user->id]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'name',
                         'description',
                         'price',
                         'category',
                         'user'
                     ]
                 ]);
    }

    /**
     * Test API can create product when authenticated.
     */
    public function test_api_can_create_product_when_authenticated()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();

        Sanctum::actingAs($user);

        $productData = [
            'name' => 'API Test Product',
            'description' => 'API Test Description',
            'price' => 149.99,
            'stock_quantity' => 20,
            'sku' => 'API123',
            'category_id' => $category->id,
            'active' => true,
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'name',
                         'price',
                         'user_id'
                     ]
                 ]);

        $this->assertDatabaseHas('products', array_merge($productData, ['user_id' => $user->id]));
    }

    /**
     * Test API requires authentication for creation.
     */
    public function test_api_requires_authentication_for_creation()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $productData = [
            'name' => 'Unauthorized Product',
            'price' => 99.99,
            'stock_quantity' => 10,
            'sku' => 'UNAUTH123',
            'category_id' => $category->id,
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(401);
    }

    /**
     * Test API can update product.
     */
    public function test_api_can_update_product()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'user_id' => $user->id]);

        Sanctum::actingAs($user);

        $updateData = [
            'name' => 'Updated Product Name',
            'description' => 'Updated Description',
            'price' => 199.99,
            'stock_quantity' => 15,
            'sku' => $product->sku, // Keep same SKU
            'category_id' => $category->id,
            'active' => true,
        ];

        $response = $this->putJson("/api/products/{$product->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data'
                 ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'price' => 199.99
        ]);
    }

    /**
     * Test API can delete product.
     */
    public function test_api_can_delete_product()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'user_id' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Produto removido com sucesso!'
                 ]);

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /**
     * Test API search functionality.
     */
    public function test_api_can_search_products()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();

        Product::factory()->create([
            'name' => 'iPhone 14',
            'category_id' => $category->id,
            'user_id' => $user->id
        ]);

        Product::factory()->create([
            'name' => 'Samsung Galaxy',
            'category_id' => $category->id,
            'user_id' => $user->id
        ]);

        $response = $this->getJson('/api/search?q=iPhone');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'category'
                         ]
                     ]
                 ]);

        $this->assertEquals(1, count($response->json('data')));
        $this->assertStringContainsString('iPhone', $response->json('data.0.name'));
    }

    /**
     * Test API validation.
     */
    public function test_api_validates_product_data()
    {
        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/products', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'price', 'stock_quantity', 'sku', 'category_id']);
    }
}
