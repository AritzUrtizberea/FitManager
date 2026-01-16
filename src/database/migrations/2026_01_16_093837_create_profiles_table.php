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
    Schema::create('profiles', function (Blueprint $table) {
        $table->id();
        // Creamos la relación 1:1 con la tabla users
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        $table->integer('age');
        $table->enum('gender', ['male', 'female', 'other']);
        $table->decimal('height', 5, 2); // Hasta 999.99 (ej: 175.50)
        $table->decimal('weight', 5, 2); // Hasta 999.99 (ej: 80.20)
        $table->string('physical_activity'); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
