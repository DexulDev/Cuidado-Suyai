<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('ingredients');
            $table->string('category');
            $table->enum('difficulty', ['fácil', 'intermedio', 'difícil']);
            $table->integer('preparation_time')->nullable();
            $table->integer('calories_per_serving')->nullable();
            $table->integer('protein')->nullable();
            $table->integer('carbohydrates')->nullable();
            $table->integer('fats')->nullable();
            $table->integer('servings')->default(1);
            $table->text('preparation')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['category', 'is_active']);
            $table->index('difficulty');
        });
    }

    public function down()
    {
        Schema::dropIfExists('foods');
    }
};
