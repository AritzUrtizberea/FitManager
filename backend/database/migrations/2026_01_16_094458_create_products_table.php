<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('barcode')->nullable(); // <--- AÑADIDO
            $table->string('image_url')->nullable();
            
            // VALORES NUTRICIONALES COMPLETOS
            $table->decimal('kcal', 8, 2)->default(0);
            $table->decimal('proteins', 8, 2)->default(0); // <--- AÑADIDO
            $table->decimal('carbs', 8, 2)->default(0);    // <--- AÑADIDO
            $table->decimal('fats', 8, 2)->default(0);     // <--- AÑADIDO

            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};