<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar categoria para teste
        $this->category = Category::create([
            'name' => 'Teste',
            'slug' => 'teste',
        ]);
    }    public function test_can_list_products()
    {
        // Criar produtos para teste
        Product::factory()->count(3)->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                            'price',
                            'stock',
                            'category_id'
                        ]
                    ]
                ]);
    }

    public function test_can_show_single_product()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'name',
                        'slug',
                        'description',
                        'price',
                                                    'stock',
                        'category_id'
                    ]
                ]);
    }

    public function test_can_get_featured_products()
    {
        // Criar produtos em destaque
        Product::factory()->count(2)->create([
            'category_id' => $this->category->id,
            'featured' => true
        ]);

        $response = $this->getJson('/api/products/featured');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'featured'
                        ]
                    ]
                ]);
    }

    public function test_admin_can_create_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        if ($admin instanceof \Illuminate\Database\Eloquent\Collection) {
            $admin = $admin->first();
        }
        if (!$admin) {
            $this->fail('Usuário admin não encontrado.');
        }
    $this->actingAs($admin instanceof \Illuminate\Contracts\Auth\Authenticatable ? $admin : new class extends \App\Models\User implements \Illuminate\Contracts\Auth\Authenticatable {}, 'sanctum');

        $productData = [
            'name' => 'Produto Teste',
            'description' => 'Descrição do produto teste',
            'price' => 99.99,
            'stock' => 50,
            'category_id' => $this->category->id,
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(201)
                ->assertJsonFragment(['name' => 'Produto Teste']);

        $this->assertDatabaseHas('products', [
            'name' => 'Produto Teste',
            'price' => 99.99
        ]);
    }

    public function test_non_admin_cannot_create_product()
    {
        $user = User::factory()->create(['role' => 'customer']);
        if ($user instanceof \Illuminate\Database\Eloquent\Collection) {
            $user = $user->first();
        }
        if (!$user) {
            $this->fail('Usuário customer não encontrado.');
        }
    $this->actingAs($user instanceof \Illuminate\Contracts\Auth\Authenticatable ? $user : new class extends \App\Models\User implements \Illuminate\Contracts\Auth\Authenticatable {}, 'sanctum');

        $productData = [
            'name' => 'Produto Teste',
            'description' => 'Descrição do produto teste',
            'price' => 99.99,
            'stock' => 50,
            'category_id' => $this->category->id,
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(403);
    }
}
