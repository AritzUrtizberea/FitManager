<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertamos la categoría ID 1 si no existe
        DB::table('categories')->insertOrIgnore([
            'id' => 1,
            'name' => 'Escaneados', // O 'General', como prefieras
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Puedes añadir más si quieres
        DB::table('categories')->insertOrIgnore([
            'id' => 2,
            'name' => 'Desayuno',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}