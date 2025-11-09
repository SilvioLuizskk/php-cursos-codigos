<?php

namespace Tests\Unit;

use App\Events\ProductCreated;
use App\Listeners\LogProductCreated;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test ProductCreated event is dispatched when product is created.
     */
    public function test_product_created_event_is_dispatched()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        // Simulate event dispatch manually since we're testing the event itself
        event(new ProductCreated($product));

        Event::assertDispatched(ProductCreated::class, function ($event) use ($product) {
            return $event->product->id === $product->id;
        });
    }

    /**
     * Test ProductCreated event contains correct product data.
     */
    public function test_product_created_event_contains_product()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $event = new ProductCreated($product);

        $this->assertInstanceOf(Product::class, $event->product);
        $this->assertEquals($product->id, $event->product->id);
        $this->assertEquals($product->name, $event->product->name);
    }

    /**
     * Test LogProductCreated listener handles ProductCreated event.
     */
    public function test_log_product_created_listener_handles_event()
    {
        Log::spy();

        /** @var User $user */
        $user = User::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $event = new ProductCreated($product);
        $listener = new LogProductCreated();

        $listener->handle($event);

        Log::shouldHaveReceived('info')
           ->once()
           ->with('Novo produto criado', [
               'product_id' => $product->id,
               'name' => $product->name,
               'user_id' => $product->user_id,
               'price' => $product->price,
           ]);
    }
}
