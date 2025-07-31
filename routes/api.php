<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FoodController;
use App\Models\Search;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/exercises', [ExerciseController::class, 'apiList']);
Route::get('/exercises/search', [ExerciseController::class, 'search']);
Route::get('/foods', [FoodController::class, 'apiList']);
Route::get('/foods/search', [FoodController::class, 'search']);

// Optional: Search analytics routes
Route::get('/search/popular/{type?}', function($type = null) {
    return Search::getPopularTerms($type);
});

Route::get('/search/stats/{type?}', function($type = null) {
    return Search::getStats($type);
});