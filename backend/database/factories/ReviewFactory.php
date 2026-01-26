<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            // Esto asigna la reseña a un usuario aleatorio de los que ya existen
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraph(1),
            'created_at' => now(),
        ];
    }
}