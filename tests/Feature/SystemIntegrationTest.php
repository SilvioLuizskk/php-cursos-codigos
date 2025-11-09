<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SystemIntegrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test complete product lifecycle through web interface.
     */
    public function test_complete_product_lifecycle_web()
    {
        // 1. Criar usuário e categoria
        /** @var User $user */
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        /** @var Category $category */
        $category = Category::factory()->create(['name' => 'Electronics']);

        // 2. Testar listagem de produtos (sem login)
        $response = $this->get('/products');
        $response->assertStatus(200);

        // 3. Tentar criar produto sem autenticação
        $response = $this->get('/products/create');
        $response->assertRedirect('/login');

        // 4. Fazer login e criar produto
        $this->actingAs($user);

        $productData = [
            'name' => 'Integration Test Product',
            'description' => 'Product created during integration test',
            'price' => 299.99,
            'stock_quantity' => 25,
            'sku' => 'INT-TEST-001',
            'category_id' => $category->id,
            'active' => 1,
        ];

        $response = $this->post('/products', $productData);
        $response->assertRedirect();

        // 5. Verificar que o produto foi criado
        $this->assertDatabaseHas('products', [
            'name' => 'Integration Test Product',
            'sku' => 'INT-TEST-001',
            'user_id' => $user->id,
        ]);

        $product = Product::where('sku', 'INT-TEST-001')->first();

        // 6. Visualizar produto
        $response = $this->get("/products/{$product->id}");
        $response->assertStatus(200)
                 ->assertSee('Integration Test Product');

        // 7. Editar produto
        $updateData = [
            'name' => 'Updated Integration Product',
            'description' => 'Updated description',
            'price' => 399.99,
            'stock_quantity' => 30,
            'sku' => 'INT-TEST-001',
            'category_id' => $category->id,
            'active' => 1,
        ];

        $response = $this->put("/products/{$product->id}", $updateData);
        $response->assertRedirect();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Integration Product',
            'price' => 399.99,
        ]);

        // 8. Buscar produto
        $response = $this->get('/products?search=Updated');
        $response->assertStatus(200)
                 ->assertSee('Updated Integration Product');

        // 9. Deletar produto
        $response = $this->delete("/products/{$product->id}");
        $response->assertRedirect();

        // Verificar soft delete
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /**
     * Test complete API workflow.
     */
    public function test_complete_api_workflow()
    {
        // 1. Criar usuário e categoria
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();

        // 2. Testar listagem pública via API
        $response = $this->getJson('/api/products');
        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data', 'pagination']);

        // 3. Autenticar via Sanctum
        Sanctum::actingAs($user);

        // 4. Criar produto via API
        $productData = [
            'name' => 'API Test Product',
            'description' => 'Created via API during integration test',
            'price' => 199.99,
            'stock_quantity' => 15,
            'sku' => 'API-TEST-001',
            'category_id' => $category->id,
            'active' => true,
        ];

        $response = $this->postJson('/api/products', $productData);
        $response->assertStatus(201)
                 ->assertJson(['success' => true])
                 ->assertJsonFragment(['name' => 'API Test Product']);

        $productId = $response->json('data.id');

        // 5. Buscar produto específico via API
        $response = $this->getJson("/api/products/{$productId}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'API Test Product']);

        // 6. Atualizar produto via API
        $updateData = array_merge($productData, [
            'name' => 'Updated API Product',
            'price' => 249.99,
        ]);

        $response = $this->putJson("/api/products/{$productId}", $updateData);
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated API Product']);

        // 7. Buscar produtos via API
        $response = $this->getJson('/api/search?q=Updated');
        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);

        // 8. Deletar produto via API
        $response = $this->deleteJson("/api/products/{$productId}");
        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        // Verificar soft delete
        $this->assertSoftDeleted('products', ['id' => $productId]);
    }

    /**
     * Test system handles validation errors correctly.
     */
    public function test_validation_handling()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        // Teste validação web
        $response = $this->post('/products', []);
        $response->assertSessionHasErrors(['name', 'price', 'stock_quantity', 'sku', 'category_id']);

        // Teste validação API
        Sanctum::actingAs($user);
        $response = $this->postJson('/api/products', []);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'price', 'stock_quantity', 'sku', 'category_id']);
    }

    /**
     * Test event system integration.
     */
    public function test_event_system_integration()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();

        // Fake events para verificar se foram disparados
        $this->expectsEvents([\App\Events\ProductCreated::class]);

        $this->actingAs($user);

        $productData = [
            'name' => 'Event Test Product',
            'description' => 'Testing event system',
            'price' => 99.99,
            'stock_quantity' => 10,
            'sku' => 'EVENT-001',
            'category_id' => $category->id,
            'active' => 1,
        ];

        $response = $this->post('/products', $productData);
        $response->assertRedirect();

        // Verificar que o produto foi criado
        $this->assertDatabaseHas('products', ['sku' => 'EVENT-001']);
    }

    /**
     * Test database relationships and constraints.
     */
    public function test_database_relationships()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();
        /** @var Product $product */
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        // Testar relacionamentos
        $this->assertInstanceOf(User::class, $product->user);
        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($user->id, $product->user->id);
        $this->assertEquals($category->id, $product->category->id);

        // Testar relacionamento inverso
        $this->assertTrue($category->products->contains($product));
    }
}
