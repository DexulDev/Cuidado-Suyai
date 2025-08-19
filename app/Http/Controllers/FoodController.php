<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

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
}