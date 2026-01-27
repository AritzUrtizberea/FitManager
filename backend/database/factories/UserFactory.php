<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    // ¡OJO AQUÍ! Asegúrate de que el nombre sea "UserFactory" 
    // y que herede de "Factory"
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'surname' => fake()->lastName(), // <--- AÑADE ESTA LÍNEA AQUÍ
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // contraseña = password
            'remember_token' => Str::random(10),
        ];
    }
    }