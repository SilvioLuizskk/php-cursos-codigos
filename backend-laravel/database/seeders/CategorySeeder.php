<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se a tabela categories tem as colunas corretas primeiro
        try {
            Category::create([
                'name' => 'Chinelos',
                'slug' => 'chinelos',
                'description' => 'Categoria principal de chinelos'
            ]);
        } catch (\Exception $e) {
            // Se falhar, tentar com estrutura mais simples
            Category::create([
                'name' => 'Chinelos',
                'slug' => 'chinelos'
            ]);
        }

        Category::firstOrCreate(['slug' => 'havaianas'], [
            'name' => 'Havaianas',
            'slug' => 'havaianas',
            'description' => 'Chinelos da marca Havaianas'
        ]);

        Category::firstOrCreate(['slug' => 'ipanema'], [
            'name' => 'Ipanema',
            'slug' => 'ipanema',
            'description' => 'Chinelos da marca Ipanema'
        ]);

        Category::firstOrCreate(['slug' => 'rider'], [
            'name' => 'Rider',
            'slug' => 'rider',
            'description' => 'Chinelos da marca Rider'
        ]);
    }
}
