<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FoodController;

Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
Route::get('/foods', [FoodController::class, 'index'])->name('foods.index');

Route::get('/', function() {
    return redirect()->route('foods.index');
});