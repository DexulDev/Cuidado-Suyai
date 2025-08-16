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
Route::get('/foods/{food}', [FoodController::class, 'show']);
Route::get('/exercises/{exercise}', [ExerciseController::class, 'show']);

// Search logging and analytics
Route::post('/log-search', function(Request $request) {
    try {
        $data = $request->validate([
            'search_type' => 'required|string|in:food,exercise',
            'query' => 'nullable|string',
            'category' => 'nullable|string',
            'results_count' => 'required|integer',
            'difficulty' => 'nullable|string',
            'filters' => 'nullable|array'
        ]);
        
        // Add IP and user agent for analytics
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = $request->header('User-Agent');
        
        Search::create($data);
        
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
    }
});

Route::get('/search/popular/{type?}', function($type = null) {
    return Search::getPopularTerms($type);
});

Route::get('/search/stats/{type?}', function($type = null) {
    return Search::getStats($type);
});