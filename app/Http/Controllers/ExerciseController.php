<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index()
    {
        return view('foods.index');
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
        
        return $results;
    }

    // Nuevo método show
    public function show(Exercise $exercise)
    {
        $exercise->load('images');
        $exercise->image_path = $exercise->getImagePath();
        // Map images with full_url like foods
        $exercise->images = $exercise->images->map(function($img){
            return [
                'id' => $img->id,
                'path' => $img->path,
                'position' => $img->position,
                'full_url' => asset('storage/exercises/' . $img->path)
            ];
        });
        return $exercise;
    }
}