<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Puedes descomentarlo si lo necesitas
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. CREAR EL ADMIN (El Jefe)
        User::create([
            'name' => 'Super',
            'surname' => 'Admin',       // <--- Añado surname para que no falle
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,         // <--- ESTA ES LA CLAVE
        ]);

        // 2. CREAR UN USUARIO NORMAL (Para pruebas)
        User::create([
            'name' => 'Usuario',
            'surname' => 'Pruebas',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);

        // 3. Crear 10 usuarios aleatorios de relleno
        $usuarios = User::factory(10)->create();

        // 4. Crear reseñas para esos usuarios aleatorios
        foreach ($usuarios as $user) {
            \App\Models\Review::create([
                'user_id' => $user->id,
                'rating' => rand(3, 5),
                'comment' => fake()->sentence(12),
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // 5. Llamar al seeder de productos (si lo tienes listo)
        $this->call([
            CategorySeeder::class, // Este te faltaba
            ProductSeeder::class,  // Este ya lo tenías
            // Agrega aquí cualquier otro seeder nuevo
        ]);
    }
}