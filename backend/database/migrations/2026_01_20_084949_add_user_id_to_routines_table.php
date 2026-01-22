<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('routines', function (Blueprint $table) {
            // Esto añade la columna user_id que faltaba en tu tabla original
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('routines', function (Blueprint $table) {
            // Esto permite borrar la relación si haces un rollback
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};