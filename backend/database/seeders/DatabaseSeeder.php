<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Creamos los 10 usuarios con nombres y apellidos aleatorios
        $usuarios = \App\Models\User::factory(10)->create();

        // 2. Creamos una reseña única para cada uno
        foreach ($usuarios as $user) {
            \App\Models\Review::create([
                'user_id' => $user->id,
                'rating' => rand(3, 5), // Unos darán 3 estrellas, otros 5
                'comment' => fake()->sentence(12), // Genera una frase aleatoria de 12 palabras
                'created_at' => now()->subDays(rand(1, 30)), // Fechas diferentes en el último mes
            ]);
        }

        $this->call(ProductSeeder::class);
    }
}