<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('category');
            $table->enum('difficulty', ['principiante', 'intermedio', 'avanzado']);
            $table->integer('duration')->nullable(); // en minutos
            $table->integer('calories_burned')->nullable();
            $table->string('equipment')->nullable();
            $table->string('muscle_group')->nullable();
            $table->string('intensity')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['category', 'is_active']);
            $table->index('difficulty');
            $table->index('muscle_group');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exercises');
    }
};
