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

    // Nuevo método show para detalles completos
    public function show(Food $food)
    {
        $food->load('images');
        $food->image_path = $food->getImagePath();
        // Añadir paths completos para imágenes secundarias
        $food->images->transform(function($img) {
            $img->full_url = asset('storage/foods/' . $img->path);
            return $img;
        });
        return $food;
    }

    private function saveSearch(Request $request, string $type, int $resultsCount)
    {
        try {
            $raw = $request->query('query');
            $searchTerm = is_string($raw) ? trim($raw) : null;

            // Guard clauses: no texto real -> no guardar
            if (!$searchTerm || $searchTerm === '' ) { return; }
            // Quitar múltiples espacios
            $searchTerm = preg_replace('/\s+/', ' ', $searchTerm);
            // Evitar queries solo de signos/puntuación
            if (!preg_match('/[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9]/u', $searchTerm)) { return; }

            // Throttle: evitar spam mismo IP / misma query en 60s
            $recentExists = Search::where('search_type', $type)
                ->where('query', $searchTerm)
                ->where('ip_address', $request->ip())
                ->where('created_at', '>', now()->subSeconds(60))
                ->exists();
            if ($recentExists) { return; }

            $filters = [];
            foreach ($request->all() as $key => $value) {
                if (!in_array($key, ['query', 'category', 'difficulty']) && !empty($value)) {
                    $filters[$key] = $value;
                }
            }

            Search::create([
                'search_type' => $type,
                'query' => $searchTerm,
                'category' => $request->query('category'),
                'difficulty' => $request->query('difficulty'),
                'results_count' => $resultsCount,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string)$request->userAgent(),0,255),
                'filters' => !empty($filters) ? $filters : null
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save search: ' . $e->getMessage());
        }
    }
}