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
        return Food::where('is_active', true)->get();
    }

    public function search(Request $request)
    {
        $query = Food::query();
        
        $query->where('is_active', true);
        
        if ($request->filled('query')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->query('query') . '%')
                  ->orWhere('description', 'like', '%' . $request->query('query') . '%')
                  ->orWhere('ingredients', 'like', '%' . $request->query('query') . '%');
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }
        
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->query('difficulty'));
        }
        
        $results = $query->get();
        
        return $results;
    }
}