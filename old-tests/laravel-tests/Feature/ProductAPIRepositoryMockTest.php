<?php

namespace Tests\Feature;

use App\Exceptions\ProductNotFoundException;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;

class ProductAPIRepositoryMockTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_show_returns_404_when_repository_throws_not_found()
    {
        $mock = Mockery::mock(ProductRepositoryInterface::class);
        $mock->shouldReceive('findOrFail')
            ->once()
            ->with(123, Mockery::type('array'))
            ->andThrow(new ProductNotFoundException('Produto não existe'));

        $this->app->instance(ProductRepositoryInterface::class, $mock);

        $response = $this->getJson('/api/products/123');

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Produto não existe']);
    }

    public function test_destroy_returns_404_when_repository_throws_not_found_for_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $mock = Mockery::mock(ProductRepositoryInterface::class);
        $mock->shouldReceive('findForUserOrFail')
            ->once()
            ->with(555, $user->id)
            ->andThrow(new ProductNotFoundException('Produto não pertence ao usuário'));

        $this->app->instance(ProductRepositoryInterface::class, $mock);

        $response = $this->deleteJson('/api/products/555');

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Produto não pertence ao usuário']);
    }
}
