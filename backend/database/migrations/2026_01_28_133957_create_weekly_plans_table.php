<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('weekly_plans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Vinculado al usuario
        $table->string('day'); // 'Lunes', 'Martes', etc.
        $table->text('meals_summary')->nullable(); // Ej: "Arroz con pollo (100g)..."
        $table->integer('calories')->default(0);   // Ej: 1854
        $table->enum('status', ['pending', 'completed'])->default('pending'); // Para el botón verde/naranja
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_plans');
    }
};
