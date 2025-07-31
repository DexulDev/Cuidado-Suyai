<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FoodController extends Controller
{
    public function index()
    {
        return view('foods.index');
    }

    public function apiList()
    {
        $foods = Food::active()->get();
        
        // Asegurar que las URLs de imagen estén disponibles
        $foods->each(function($food) {
            $food->image_path = $food->getImagePath();
        });
        
        return $foods;
    }

    public function search(Request $request)
    {
        $query = Food::active();
        
        if ($request->filled('query')) {
            $searchTerm = $request->query('query');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('ingredients', 'like', '%' . $searchTerm . '%');
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }
        
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->query('difficulty'));
        }
        
        $results = $query->orderBy('name')->get();
        
        // Asegurar que las URLs de imagen estén disponibles
        $results->each(function($food) {
            $food->image_path = $food->getImagePath();
        });
        
        // Save search to database
        $this->saveSearch($request, 'food', $results->count());
        
        return $results;
    }

    private function saveSearch(Request $request, string $type, int $resultsCount)
    {
        try {
            $filters = [];
            
            // Collect all search parameters except query and category
            foreach ($request->all() as $key => $value) {
                if (!in_array($key, ['query', 'category', 'difficulty']) && !empty($value)) {
                    $filters[$key] = $value;
                }
            }

            Search::create([
                'search_type' => $type,
                'query' => $request->query('query'),
                'category' => $request->query('category'),
                'difficulty' => $request->query('difficulty'),
                'results_count' => $resultsCount,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'filters' => !empty($filters) ? $filters : null
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the search functionality
            Log::error('Failed to save search: ' . $e->getMessage());
        }
    }
}