<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criar usuários
        $user1 = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
        ]);

        // Criar categorias
        $electronics = Category::create([
            'name' => 'Eletrônicos',
            'description' => 'Dispositivos eletrônicos e gadgets',
            'active' => true,
        ]);

        $books = Category::create([
            'name' => 'Livros',
            'description' => 'Livros de todos os gêneros',
            'active' => true,
        ]);

        $clothing = Category::create([
            'name' => 'Roupas',
            'description' => 'Vestuário em geral',
            'active' => true,
        ]);

        // Criar produtos para Electronics
        Product::create([
            'name' => 'iPhone 14 Pro',
            'description' => 'O mais novo iPhone da Apple com tecnologia avançada',
            'price' => 7999.99,
            'stock_quantity' => 50,
            'sku' => 'IPHONE14PRO',
            'active' => true,
            'category_id' => $electronics->id,
            'user_id' => $user1->id,
        ]);

        Product::create([
            'name' => 'Samsung Galaxy S23',
            'description' => 'Smartphone Android flagship da Samsung',
            'price' => 5999.99,
            'stock_quantity' => 30,
            'sku' => 'GALAXYS23',
            'active' => true,
            'category_id' => $electronics->id,
            'user_id' => $user1->id,
        ]);

        // Criar produtos para Books
        Product::create([
            'name' => 'Clean Code',
            'description' => 'Livro sobre práticas de programação limpa',
            'price' => 89.99,
            'stock_quantity' => 100,
            'sku' => 'CLEANCODE',
            'active' => true,
            'category_id' => $books->id,
            'user_id' => $user1->id,
        ]);
    }
}
