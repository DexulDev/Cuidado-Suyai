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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category'); // desayunos, almuerzos, cenas, snacks, etc.
            $table->float('calories');
            $table->float('protein')->nullable();
            $table->float('carbohydrates')->nullable();
            $table->float('fats')->nullable();
            $table->text('ingredients')->nullable(); // Lista de ingredientes
            $table->text('preparation')->nullable(); // Instrucciones de preparación
            $table->integer('preparation_time')->nullable(); // Tiempo de preparación en minutos
            $table->integer('servings')->nullable(); // Número de porciones
            $table->string('difficulty')->nullable(); // fácil, intermedio, difícil
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};