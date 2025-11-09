<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar a primeira categoria disponível
        $category = Category::first();

        if (!$category) {
            // Criar uma categoria se não existir
            $category = Category::create([
                'name' => 'Chinelos',
                'slug' => 'chinelos'
            ]);
        }

        $products = [
            [
                'name' => 'Chinelo Havaianas Brasil',
                'slug' => 'chinelo-havaianas-brasil',
                'description' => 'Chinelo icônico do Brasil com as cores da bandeira nacional. Feito com material de alta qualidade e conforto incomparável.',
                'short_description' => 'Chinelo clássico brasileiro',
                'price' => 29.99,
                'stock' => 50,
                'category_id' => $category->id,
                'sku' => 'HAV-BR-001',
                'featured' => true,
                'status' => 'active'
            ],
            [
                'name' => 'Chinelo Ipanema Fashion',
                'slug' => 'chinelo-ipanema-fashion',
                'description' => 'Chinelo moderno com estampa exclusiva da praia de Ipanema. Design contemporâneo com muito estilo e personalidade.',
                'short_description' => 'Style carioca autêntico',
                'price' => 39.99,
                'stock' => 30,
                'category_id' => $category->id,
                'sku' => 'IPA-FA-001',
                'featured' => true,
                'status' => 'active'
            ],
            [
                'name' => 'Chinelo Rider Energy',
                'slug' => 'chinelo-rider-energy',
                'description' => 'Chinelo esportivo com tecnologia de amortecimento. Ideal para atividades ao ar livre e uso diário.',
                'short_description' => 'Conforto e tecnologia',
                'price' => 45.99,
                'stock' => 25,
                'category_id' => $category->id,
                'sku' => 'RID-EN-001',
                'featured' => false,
                'status' => 'active'
            ],
            [
                'name' => 'Chinelo Personalizado Amor',
                'slug' => 'chinelo-personalizado-amor',
                'description' => 'Chinelo personalizável com tema romântico. Perfeito para presentear quem você ama com uma mensagem especial.',
                'short_description' => 'Presente personalizado',
                'price' => 55.99,
                'stock' => 15,
                'category_id' => $category->id,
                'sku' => 'PER-AM-001',
                'featured' => true,
                'status' => 'active'
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
