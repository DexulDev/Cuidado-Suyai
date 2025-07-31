<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FoodController;

Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
Route::get('/foods', [FoodController::class, 'index'])->name('foods.index');

Route::get('/', function() {
    return redirect()->route('foods.index');
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [App\Http\Controllers\AdminController::class, 'login'])->name('login');
    Route::post('login', [App\Http\Controllers\AdminController::class, 'authenticate'])->name('authenticate');
    
    Route::middleware(App\Http\Middleware\AdminAuth::class)->group(function () {
        Route::get('dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('logout');
        Route::get('change-password', [App\Http\Controllers\AdminController::class, 'changePassword'])->name('change-password');
        Route::post('change-password', [App\Http\Controllers\AdminController::class, 'updatePassword'])->name('update-password');
        
        // Food management
        Route::get('foods/create', [App\Http\Controllers\AdminController::class, 'createFood'])->name('foods.create');
        Route::post('foods', [App\Http\Controllers\AdminController::class, 'storeFood'])->name('foods.store');
        
        // Exercise management
        Route::get('exercises/create', [App\Http\Controllers\AdminController::class, 'createExercise'])->name('exercises.create');
        Route::post('exercises', [App\Http\Controllers\AdminController::class, 'storeExercise'])->name('exercises.store');
    });
});