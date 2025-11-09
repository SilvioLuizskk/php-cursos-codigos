<?php

namespace Tests\Feature;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Exceptions\ProductNotFoundException;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ProductControllerRepositoryMockTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_show_web_returns_404_when_not_found()
    {
        $mock = Mockery::mock(ProductRepositoryInterface::class);
        $mock->shouldReceive('findOrFail')
            ->once()
            ->with(999, Mockery::type('array'))
            ->andThrow(new ProductNotFoundException('Produto não encontrado'));

        $this->app->instance(ProductRepositoryInterface::class, $mock);

        $response = $this->get('/products/999');
        $response->assertStatus(404);
    }

    public function test_edit_redirects_to_login_if_not_authenticated()
    {
        $response = $this->get('/products/1/edit');
        $response->assertRedirect('/login');
    }

    public function test_destroy_returns_404_when_not_owned()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $mock = Mockery::mock(ProductRepositoryInterface::class);
        $mock->shouldReceive('findForUserOrFail')
            ->once()
            ->with(777, $user->id)
            ->andThrow(new ProductNotFoundException('Produto não encontrado para usuário'));

        $this->app->instance(ProductRepositoryInterface::class, $mock);

        $response = $this->delete('/products/777');
        $response->assertStatus(404);
    }
}
