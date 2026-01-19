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
        // Relación con el usuario
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Campos adicionales
        $table->string('phone');
        $table->string('sex');
        $table->float('weight');
        $table->integer('height');
        $table->string('activity');
        
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
