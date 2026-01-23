<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // 1. Aseguramos que existan las categorías básicas y guardamos sus IDs
        // Usamos firstOrCreate para no duplicarlas si ya existen
        $catCarbs = Category::firstOrCreate(['name' => 'Carbohidratos'])->id;
        $catProtes = Category::firstOrCreate(['name' => 'Proteínas'])->id;
        $catGrasas = Category::firstOrCreate(['name' => 'Grasas'])->id;
        $catVerduras = Category::firstOrCreate(['name' => 'Verduras'])->id;
        $catLacteos = Category::firstOrCreate(['name' => 'Lácteos'])->id;

        $now = Carbon::now();

        // 2. Insertamos 10 productos
        // Usamos insert() para evitar problemas de $fillable
        DB::table('products')->insert([
            [
                'name' => 'Pechuga de Pollo (100g)',
                'kcal' => 165,
                'proteins' => 31.0,
                'carbs' => 0.0,
                'fats' => 3.6,
                'barcode' => '8410000001',
                'category_id' => $catProtes,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Arroz Blanco Cocido (100g)',
                'kcal' => 130,
                'proteins' => 2.7,
                'carbs' => 28.0,
                'fats' => 0.3,
                'barcode' => '8410000002',
                'category_id' => $catCarbs,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Avena en Copos (100g)',
                'kcal' => 389,
                'proteins' => 16.9,
                'carbs' => 66.3,
                'fats' => 6.9,
                'barcode' => '8410000003',
                'category_id' => $catCarbs,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Huevo Mediano (1 ud)',
                'kcal' => 70,
                'proteins' => 6.0,
                'carbs' => 0.5,
                'fats' => 5.0,
                'barcode' => '8410000004',
                'category_id' => $catProtes,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Salmón Fresco (100g)',
                'kcal' => 208,
                'proteins' => 20.0,
                'carbs' => 0.0,
                'fats' => 13.0,
                'barcode' => '8410000005',
                'category_id' => $catGrasas,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Brócoli (100g)',
                'kcal' => 34,
                'proteins' => 2.8,
                'carbs' => 7.0,
                'fats' => 0.4,
                'barcode' => '8410000006',
                'category_id' => $catVerduras,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Yogur Griego Natural (100g)',
                'kcal' => 59,
                'proteins' => 10.0,
                'carbs' => 3.6,
                'fats' => 0.4,
                'barcode' => '8410000007',
                'category_id' => $catLacteos,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Plátano (1 ud media)',
                'kcal' => 105,
                'proteins' => 1.3,
                'carbs' => 27.0,
                'fats' => 0.3,
                'barcode' => '8410000008',
                'category_id' => $catCarbs,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Aguacate (100g)',
                'kcal' => 160,
                'proteins' => 2.0,
                'carbs' => 8.5,
                'fats' => 15.0,
                'barcode' => '8410000009',
                'category_id' => $catGrasas,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Batido Proteína Whey (1 scoop)',
                'kcal' => 120,
                'proteins' => 24.0,
                'carbs' => 3.0,
                'fats' => 1.0,
                'barcode' => '8410000010',
                'category_id' => $catProtes,
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }
}