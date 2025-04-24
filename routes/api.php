<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FoodController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/exercises', [ExerciseController::class, 'apiList']);
Route::get('/exercises/search', [ExerciseController::class, 'search']);
Route::get('/foods', [FoodController::class, 'apiList']);
Route::get('/foods/search', [FoodController::class, 'search']);