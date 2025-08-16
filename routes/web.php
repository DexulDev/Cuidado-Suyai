<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\AdminController;

Route::get('/inicio', [FoodController::class, 'index'])->name('foods.index');

Route::get('/', function() {
    return redirect()->route('foods.index');
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [App\Http\Controllers\AdminController::class, 'login'])->name('login');
    Route::post('login', [App\Http\Controllers\AdminController::class, 'authenticate'])->name('authenticate');
    
    Route::middleware(App\Http\Middleware\AdminAuth::class)->group(function () {
        Route::get('dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('logout'); // cambiado a POST
        Route::get('change-password', [App\Http\Controllers\AdminController::class, 'changePassword'])->name('change-password');
        Route::post('change-password', [App\Http\Controllers\AdminController::class, 'updatePassword'])->name('update-password');
        
        // Food management
        Route::get('foods/create', [App\Http\Controllers\AdminController::class, 'createFood'])->name('foods.create');
        Route::post('foods', [App\Http\Controllers\AdminController::class, 'storeFood'])->name('foods.store');
        Route::patch('foods/{food}', [App\Http\Controllers\AdminController::class, 'updateFood'])->name('foods.update');
        Route::post('foods/{food}', [App\Http\Controllers\AdminController::class, 'updateFood']); // for FormData with _method fallback
        Route::delete('foods/{food}', [App\Http\Controllers\AdminController::class, 'destroyFood'])->name('foods.destroy');
        Route::delete('food-images/{image}', [App\Http\Controllers\AdminController::class, 'destroyFoodImage'])->name('foods.images.destroy');
        
        // Exercise management
        Route::get('exercises/create', [App\Http\Controllers\AdminController::class, 'createExercise'])->name('exercises.create');
        Route::post('exercises', [App\Http\Controllers\AdminController::class, 'storeExercise'])->name('exercises.store');
        Route::get('foods/list/json', [AdminController::class, 'apiFoods'])->name('foods.json');
        Route::get('exercises/list/json', [AdminController::class, 'apiExercises'])->name('exercises.json');
        Route::patch('exercises/{exercise}', [AdminController::class, 'updateExercise'])->name('exercises.update');
        Route::post('exercises/{exercise}', [AdminController::class, 'updateExercise']);
        Route::delete('exercise-images/{image}', [AdminController::class, 'destroyExerciseImage'])->name('exercises.images.destroy');
        
        // Analytics
        Route::get('api/searches', [AdminController::class, 'apiSearches'])->name('api.searches');
    });
});