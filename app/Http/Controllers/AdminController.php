<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Exercise;
use App\Models\ExerciseImage;
use App\Models\FoodImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function login()
    {
        $adminUser = User::firstOrCreate(['email' => 'admin@system.local'], [
            'name' => 'Administrador',
            'password' => Hash::make(Str::random(16)),
            'admin_password' => null,
        ]);
        if (is_null($adminUser->admin_password)) {
            Session::put('admin_authenticated', true);
            Session::put('needs_password_change', true);
            return redirect()->route('admin.change-password')->with('success','Configura tu primera contraseña');
        }
        if (Session::get('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate(['password' => 'required']);
        $key = 'admin-login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['password' => 'Demasiados intentos. Intenta en ' . $seconds . ' seg.'])->with('error','Bloqueado temporalmente');
        }

        // Obtener (o crear) registro único de admin
        $adminUser = User::firstOrCreate(['email' => 'admin@system.local'], [
            'name' => 'Administrador',
            'password' => Hash::make(Str::random(16)), // no se usa para login normal
            'admin_password' => null,
        ]);

        $needsSet = is_null($adminUser->admin_password);

        // Primer acceso: no hay contraseña -> autenticar sesión y forzar redirección a cambio
        if ($needsSet) {
            RateLimiter::clear($key);
            $request->session()->regenerate();
            Session::put('admin_authenticated', true);
            Session::put('needs_password_change', true);
            return redirect()->route('admin.change-password')->with('success','Configura la primera contraseña');
        }

        $valid = Hash::check($request->password, $adminUser->admin_password);

        if ($valid) {
            RateLimiter::clear($key);
            $request->session()->regenerate();
            Session::put('admin_authenticated', true);
            if (!$adminUser->admin_password_set_at) {
                Session::put('needs_password_change', true);
                return redirect()->route('admin.change-password');
            }
            return redirect()->route('admin.dashboard')->with('success','Ingreso correcto');
        }

        RateLimiter::hit($key, 900);
        return back()->withErrors(['password' => 'Contraseña incorrecta'])->with('error','Credenciales inválidas');
    }

    public function changePassword()
    {
        // Solo accesible autenticado, pero adicionalmente forzamos si flag activo
        $isFirstTime = Session::has('needs_password_change') ? true : false;
        return view('admin.change-password', ['first_time' => $isFirstTime]);
    }

    public function updatePassword(Request $request)
    {
        // Simplify validation - only require minimum 5 characters and matching confirmation
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'new_password' => 'required|min:5|same:new_password_confirmation',
            'new_password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $adminUser = User::where('email','admin@system.local')->firstOrFail();
            $adminUser->admin_password = Hash::make($request->new_password);
            $adminUser->admin_password_set_at = Carbon::now();
            $adminUser->save();

            Session::forget('needs_password_change');
            Session::put('admin_authenticated', true);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Contraseña actualizada correctamente'
                ]);
            }
            
            return redirect()->route('admin.dashboard')->with('success', 'Contraseña actualizada correctamente');
        } catch (\Exception $e) {

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['general' => 'Error al actualizar la contraseña: ' . $e->getMessage()]
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error al actualizar la contraseña: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        $foodsCount = Food::count();
        $exercisesCount = Exercise::count();
        $foods = Food::with('images')->orderByDesc('created_at')->get();
        $exercises = Exercise::orderByDesc('created_at')->get();
        
        // Estadísticas de búsquedas
        $searchesCount = \App\Models\Search::count();
        $searchesToday = \App\Models\Search::whereDate('created_at', today())->count();
        
        return view('admin.dashboard', compact(
            'foodsCount', 
            'exercisesCount', 
            'foods', 
            'exercises',
            'searchesCount',
            'searchesToday'
        ));
    }
    
    public function searchAnalytics()
    {
        $searches = \App\Models\Search::orderBy('created_at', 'desc')
            ->paginate(50);
            
        $stats = [
            'total' => \App\Models\Search::count(),
            'food' => \App\Models\Search::foodSearches()->count(),
            'exercise' => \App\Models\Search::exerciseSearches()->count(),
            'popular_terms' => \App\Models\Search::getPopularTerms(null, 10),
            'today' => \App\Models\Search::whereDate('created_at', today())->count()
        ];
        
        if (request()->wantsJson()) {
            return response()->json([
                'searches' => $searches,
                'stats' => $stats
            ]);
        }
        
        return view('admin.search-analytics', compact('searches', 'stats'));
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('admin.login');
    }

    public function createFood()
    {
        return view('admin.create-food');
    }

    public function storeFood(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|string',
            'category' => 'required|string',
            'difficulty' => 'required|in:fácil,intermedio,difícil',
            'preparation_time' => 'nullable|integer',
            'calories_per_serving' => 'nullable|integer',
            'protein' => 'nullable|integer',
            'carbohydrates' => 'nullable|integer',
            'fats' => 'nullable|integer',
            'preparation' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = $request->except('images');
        $data['is_active'] = true;
        $data['servings'] = $data['servings'] ?? 1;
        $data['image_url'] = null; // will set after first image stored

        $food = Food::create($data);

        if ($request->hasFile('images')) {
            // Asegurar directorio
            if (!Storage::disk('public')->exists('foods')) {
                Storage::disk('public')->makeDirectory('foods');
            }
            $ts = time();
            foreach ($request->file('images') as $index => $image) {
                try {
                    $ext = strtolower($image->getClientOriginalExtension() ?: 'jpg');
                    $filename = 'food_' . $food->id . '_' . $ts . '_' . $index . '.' . $ext;
                    Storage::disk('public')->putFileAs('foods', $image, $filename);
                    FoodImage::create([
                        'food_id' => $food->id,
                        'path' => $filename,
                        'position' => $index,
                    ]);
                    if ($index === 0) {
                        $food->image_url = $filename;
                    }
                } catch (\Exception $e) {
                    Log::error('Error al guardar imagen de comida', [ 'msg' => $e->getMessage() ]);
                }
            }
            $food->save();
        }

        return redirect()->route('admin.dashboard')->with('success', 'Comida agregada correctamente');
    }

    public function updateFood(Request $request, Food $food)
    {

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'ingredients' => 'sometimes|required|string',
            'category' => 'sometimes|required|string',
            'difficulty' => 'sometimes|required|in:fácil,intermedio,difícil',
            'preparation_time' => 'nullable|integer',
            'calories_per_serving' => 'nullable|integer',
            'protein' => 'nullable|integer',
            'carbohydrates' => 'nullable|integer',
            'fats' => 'nullable|integer',
            'preparation' => 'nullable|string',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'delete_images' => 'array',
            'delete_images.*' => 'integer|exists:food_images,id',
            'image_order' => 'nullable|string' // comma separated IDs
        ]);

        $food->fill($request->only([
            'name','description','ingredients','category','difficulty','preparation_time','calories_per_serving','protein','carbohydrates','fats','preparation'
        ]));
        $food->save();

        // Delete individual images
        $deleteIds = $request->input('delete_images', []);
        if (!empty($deleteIds)) {
            $imagesToDelete = FoodImage::whereIn('id', $deleteIds)->where('food_id',$food->id)->get();
            foreach ($imagesToDelete as $img) {
                Storage::disk('public')->delete('foods/' . $img->path);
                $img->delete();
            }
        }

        // Add new images
        if ($request->hasFile('new_images')) {
            $currentMax = (int) FoodImage::where('food_id',$food->id)->max('position');
            foreach ($request->file('new_images') as $index => $image) {
                try {
                    $filename = 'food_' . $food->id . '_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                    Storage::disk('public')->putFileAs('foods', $image, $filename, 'public');
                    try {
                        if (class_exists('Spatie\\LaravelImageOptimizer\\Facades\\ImageOptimizer')) {
                            $optimizer = resolve('Spatie\\LaravelImageOptimizer\\Facades\\ImageOptimizer');
                            if (method_exists($optimizer, 'optimize')) {
                                $optimizer::optimize(storage_path('app/public/foods/' . $filename));
                            }
                        }
                    } catch (\Throwable $e) {}
                    FoodImage::create([
                        'food_id' => $food->id,
                        'path' => $filename,
                        'position' => ++$currentMax,
                    ]);
                    if (!$food->image_url) { $food->image_url = $filename; $food->save(); }
                } catch (\Exception $e) {
                    Log::error('Error agregando nueva imagen comida: ' . $e->getMessage());
                }
            }
        }

        // Reorder
        if ($request->filled('image_order')) {
            $orderIds = array_filter(explode(',', $request->input('image_order')));
            foreach ($orderIds as $pos => $id) {
                FoodImage::where('id',$id)->where('food_id',$food->id)->update(['position' => $pos]);
            }
            // Reset cover to first image by new order
            $first = FoodImage::where('food_id',$food->id)->orderBy('position')->first();
            if ($first) { $food->image_url = $first->path; $food->save(); }
        }

        return response()->json(['message' => 'Comida actualizada','food' => $food->load('images')]);
    }

    public function destroyFood(Food $food)
    {
        try {
            foreach ($food->images as $img) {
                Storage::delete('public/foods/' . $img->path);
                $img->delete();
            }
            if ($food->image_url) {
                Storage::delete('public/foods/' . $food->image_url);
            }
            $food->delete();
            return redirect()->back()->with('success', 'Comida eliminada');
        } catch (\Exception $e) {
            Log::error('Error eliminando comida: ' . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo eliminar la comida');
        }
    }

    public function createExercise()
    {
        return view('admin.create-exercise');
    }

    public function storeExercise(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'difficulty' => 'required|in:principiante,intermedio,avanzado',
            'duration' => 'nullable|integer',
            'calories_burned' => 'nullable|integer',
            'equipment' => 'nullable|string',
            'muscle_group' => 'nullable|string',
            'intensity' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = $request->except('images');
        $data['is_active'] = true;
        $data['image_url'] = null;

        $exercise = Exercise::create($data);

        if (!$request->hasFile('images')) {
            Log::warning('storeExercise without images payload', ['FILES_GLOBAL' => $_FILES]);
        }

        if ($request->hasFile('images')) {
            if (!Storage::disk('public')->exists('exercises')) {
                Storage::disk('public')->makeDirectory('exercises');
            }
            $ts = time();
            foreach ($request->file('images') as $index => $image) {
                if (!$image->isValid()) {
                    Log::error('Imagen inválida en storeExercise', ['index' => $index, 'error' => $image->getErrorMessage()]);
                    continue;
                }
                try {
                    $ext = strtolower($image->getClientOriginalExtension() ?: 'jpg');
                    $filename = 'exercise_' . $exercise->id . '_' . $ts . '_' . $index . '.' . $ext;
                    Storage::disk('public')->putFileAs('exercises', $image, $filename);
                    ExerciseImage::create([
                        'exercise_id' => $exercise->id,
                        'path' => $filename,
                        'position' => $index,
                    ]);
                    if ($index === 0) {
                        $exercise->image_url = $filename;
                    }
                } catch (\Exception $e) {
                    Log::error('Error guardando imagen ejercicio', ['msg' => $e->getMessage()]);
                }
            }
            $exercise->save();
        }
        return redirect()->route('admin.dashboard')->with('success', 'Ejercicio agregado correctamente');
    }

    public function destroyFoodImage(FoodImage $image)
    {
        $foodId = $image->food_id;
        Storage::disk('public')->delete('foods/' . $image->path);
        $image->delete();
        // Ajustar posiciones consecutivas
        $remaining = FoodImage::where('food_id',$foodId)->orderBy('position')->get();
        foreach ($remaining as $i => $img) { $img->update(['position' => $i]); }
        $food = Food::find($foodId);
        if ($food) {
            $first = FoodImage::where('food_id',$foodId)->orderBy('position')->first();
            $food->image_url = $first?->path;
            $food->save();
        }
        return response()->json(['message' => 'Imagen eliminada']);
    }
    
    public function destroyExerciseImage(ExerciseImage $image)
    {
        $exerciseId = $image->exercise_id;
        Storage::disk('public')->delete('exercises/' . $image->path);
        $image->delete();
        // Ajustar posiciones consecutivas
        $remaining = ExerciseImage::where('exercise_id', $exerciseId)->orderBy('position')->get();
        foreach ($remaining as $i => $img) { $img->update(['position' => $i]); }
        $exercise = Exercise::find($exerciseId);
        if ($exercise) {
            $first = ExerciseImage::where('exercise_id', $exerciseId)->orderBy('position')->first();
            $exercise->image_url = $first?->path;
            $exercise->save();
        }
        return response()->json(['message' => 'Imagen eliminada']);
    }

    public function apiFoods()
    {
        return Food::with('images')->orderByDesc('created_at')->get();
    }

    public function apiExercises()
    {
        return Exercise::with('images')->orderByDesc('created_at')->get();
    }
    
    public function apiSearches(Request $request)
    {
        $searchType = $request->input('searchType');
        $category = $request->input('category');
        $period = $request->input('period');
        $sort = $request->input('sort', 'newest');
        $page = $request->input('page', 1);
        $perPage = 15;
        
        $query = \App\Models\Search::query();
        
        // Filters
        if ($searchType) {
            $query->where('search_type', $searchType);
        }
        
        if ($category) {
            $query->where('category', $category);
        }
        
        // Period filter
        if ($period) {
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', now()->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
            }
        }
        
        // Sorting
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at');
                break;
            case 'popular':
                $query->orderByDesc('results_count');
                break;
            default: // newest
                $query->orderByDesc('created_at');
                break;
        }
        
        // Get total records for pagination
        $totalRecords = $query->count();
        $totalPages = ceil($totalRecords / $perPage);
        
        // Get paginated results
        $searches = $query->skip(($page - 1) * $perPage)
                          ->take($perPage)
                          ->get();
        
        // Get stats
        $stats = \App\Models\Search::getStats();
        
        // Get popular terms
        $popularTerms = \App\Models\Search::getPopularTerms();
        
        // Get distinct categories
        $categories = \App\Models\Search::distinct('category')->whereNotNull('category')->pluck('category');
        
        return response()->json([
            'searches' => $searches,
            'pagination' => [
                'current_page' => (int) $page,
                'total_pages' => $totalPages,
                'total_records' => $totalRecords,
                'per_page' => $perPage
            ],
            'stats' => $stats,
            'popular_terms' => $popularTerms,
            'categories' => $categories
        ]);
    }

    public function updateExercise(Request $request, Exercise $exercise)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category' => 'sometimes|required|string',
            'difficulty' => 'sometimes|required|in:principiante,intermedio,avanzado',
            'duration' => 'nullable|integer',
            'calories_burned' => 'nullable|integer',
            'equipment' => 'nullable|string',
            'muscle_group' => 'nullable|string',
            'intensity' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'nullable|integer'
        ]);

        $exercise->fill($request->only(['name','description','category','difficulty','duration','calories_burned','equipment','muscle_group','intensity']));

        // Procesar eliminación de imágenes si se solicita
        if ($request->has('delete_images') && is_array($request->delete_images)) {
            foreach ($request->delete_images as $imageId) {
                $image = ExerciseImage::find($imageId);
                if ($image && $image->exercise_id == $exercise->id) {
                    try {
                        // Eliminar el archivo físico
                        @unlink(public_path('storage/exercises/' . $image->path));
                        // Eliminar el registro
                        $image->delete();
                    } catch (\Exception $e) {
                        Log::error('Error eliminando imagen ejercicio: ' . $e->getMessage());
                    }
                }
            }
            
            // Reordenar posiciones
            $remaining = ExerciseImage::where('exercise_id', $exercise->id)
                ->orderBy('position')
                ->get();
                
            foreach ($remaining as $i => $img) {
                $img->update(['position' => $i]);
            }
            
            // Actualizar la imagen principal
            $firstImage = ExerciseImage::where('exercise_id', $exercise->id)
                ->orderBy('position')
                ->first();
                
            $exercise->image_url = $firstImage ? $firstImage->path : null;
        }

        // Agregar nuevas imágenes
        if ($request->hasFile('images')) {
            $lastPosition = ExerciseImage::where('exercise_id', $exercise->id)
                ->max('position') ?? -1;
                
            foreach ($request->file('images') as $index => $image) {
                try {
                    $position = $lastPosition + $index + 1;
                    $filename = 'exercise_' . $exercise->id . '_' . time() . '_' . $position . '.' . $image->getClientOriginalExtension();
                    
                    $publicStoragePath = public_path('storage/exercises');
                    if (!File::exists($publicStoragePath)) { 
                        File::makeDirectory($publicStoragePath, 0755, true); 
                    }
                    
                    $image->move($publicStoragePath, $filename);
                    chmod($publicStoragePath . '/' . $filename, 0644);
                    
                    // Crear entrada en la tabla de imágenes
                    ExerciseImage::create([
                        'exercise_id' => $exercise->id,
                        'path' => $filename,
                        'position' => $position,
                    ]);
                    
                    // Si no hay imagen principal, establecer esta
                    if (!$exercise->image_url) {
                        $exercise->image_url = $filename;
                    }
                    
                } catch (\Exception $e) {
                    Log::error('Error agregando imagen ejercicio: ' . $e->getMessage());
                }
            }
        }
        $exercise->save();
        $exercise->image_path = $exercise->getImagePath();
        return response()->json(['message' => 'Ejercicio actualizado', 'exercise' => $exercise]);
    }
}
