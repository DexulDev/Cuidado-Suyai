<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index()
    {
        return view('exercises.index');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $muscleGroup = $request->input('muscle_group');
        $difficulty = $request->input('difficulty');
        
        $exercises = Exercise::when($query, function($q) use ($query) {
                        return $q->where('name', 'LIKE', "%{$query}%");
                    })
                    ->when($muscleGroup, function($q) use ($muscleGroup) {
                        return $q->where('muscle_group', $muscleGroup);
                    })
                    ->when($difficulty, function($q) use ($difficulty) {
                        return $q->where('difficulty', $difficulty);
                    })
                    ->get();
        
        return response()->json($exercises);
    }

    public function apiList()
    {
        $exercises = Exercise::all();
        return response()->json($exercises);
    }

    public function create()
    {
        return view('exercises.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group' => 'required|string',
            'difficulty' => 'required|in:principiante,intermedio,avanzado',
            'duration' => 'required|integer',
            'intensity' => 'required|string',
        ]);

        Exercise::create($request->all());
        return redirect()->route('exercises.index')->with('success', 'Ejercicio creado exitosamente.');
    }
}