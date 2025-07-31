<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchesTable extends Migration
{
    public function up()
    {
        Schema::create('searches', function (Blueprint $table) {
            $table->id();
            $table->string('search_type'); // 'food' or 'exercise'
            $table->string('query')->nullable(); // search term
            $table->string('category')->nullable(); // category/muscle_group
            $table->string('difficulty')->nullable(); // difficulty level
            $table->integer('results_count')->default(0); // number of results found
            $table->string('ip_address')->nullable(); // user IP for analytics
            $table->string('user_agent')->nullable(); // browser info
            $table->json('filters')->nullable(); // additional filters as JSON
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('search_type');
            $table->index('query');
            $table->index('category');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('searches');
    }
}
