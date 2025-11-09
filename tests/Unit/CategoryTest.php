<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test category creation.
     */
    public function test_category_creation()
    {
        $category = Category::create([
            'name' => 'Electronics',
            'description' => 'Electronic devices and gadgets',
            'active' => true,
        ]);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Electronics', $category->name);
        $this->assertTrue($category->active);
    }

    /**
     * Test category has many products relationship.
     */
    public function test_category_has_many_products()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $category = Category::factory()->create();

        Product::factory()->count(3)->create([
            'category_id' => $category->id,
            'user_id' => $user->id
        ]);

        $this->assertCount(3, $category->products);
        $this->assertInstanceOf(Product::class, $category->products->first());
    }

    /**
     * Test category active scope.
     */
    public function test_category_active_scope()
    {
        Category::factory()->create(['active' => true]);
        Category::factory()->create(['active' => false]);
        Category::factory()->create(['active' => true]);

        $activeCategories = Category::active()->get();

        $this->assertCount(2, $activeCategories);
        foreach ($activeCategories as $category) {
            $this->assertTrue($category->active);
        }
    }

    /**
     * Test category search scope.
     */
    public function test_category_search_scope()
    {
        Category::factory()->create(['name' => 'Electronics']);
        Category::factory()->create(['name' => 'Books']);
        Category::factory()->create(['name' => 'Electronic Music']);

        $searchResults = Category::search('Electronic')->get();

        $this->assertCount(2, $searchResults);
    }
}
