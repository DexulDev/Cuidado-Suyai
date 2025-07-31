<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExerciseController extends Controller
{
    public function index()
    {
        return view('exercises.index');
    }

    public function apiList()
    {
        $exercises = Exercise::active()->get();
        
        // Asegurar que las URLs de imagen estén disponibles
        $exercises->each(function($exercise) {
            $exercise->image_path = $exercise->getImagePath();
        });
        
        return $exercises;
    }

    public function search(Request $request)
    {
        $query = Exercise::active();
        
        if ($request->filled('query')) {
            $searchTerm = $request->query('query');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('equipment', 'like', '%' . $searchTerm . '%');
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }
        
        if ($request->filled('muscle_group')) {
            $query->where('muscle_group', $request->query('muscle_group'));
        }
        
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->query('difficulty'));
        }
        
        $results = $query->orderBy('name')->get();
        
        // Asegurar que las URLs de imagen estén disponibles
        $results->each(function($exercise) {
            $exercise->image_path = $exercise->getImagePath();
        });
        
        // Save search to database
        $this->saveSearch($request, 'exercise', $results->count());
        
        return $results;
    }

    private function saveSearch(Request $request, string $type, int $resultsCount)
    {
        try {
            $filters = [];
            
            // Collect all search parameters except query and main filters
            foreach ($request->all() as $key => $value) {
                if (!in_array($key, ['query', 'category', 'muscle_group', 'difficulty']) && !empty($value)) {
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