<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function login()
    {
        if (Session::get('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $defaultPassword = 'admin';
        $storedPassword = config('app.admin_password', $defaultPassword);

        if ($request->password === $storedPassword) {
            Session::put('admin_authenticated', true);
            
            if ($storedPassword === $defaultPassword) {
                Session::put('needs_password_change', true);
                return redirect()->route('admin.change-password');
            }
            
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['password' => 'Contraseña incorrecta']);
    }

    public function changePassword()
    {
        return view('admin.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:8|confirmed'
        ]);

        // Guardar la nueva contraseña en el archivo .env
        $envPath = base_path('.env');
        $envContent = File::get($envPath);
        
        // Si existe la línea ADMIN_PASSWORD, actualizarla; si no, agregarla
        if (strpos($envContent, 'ADMIN_PASSWORD=') !== false) {
            $envContent = preg_replace('/ADMIN_PASSWORD=.*/', 'ADMIN_PASSWORD=' . $request->new_password, $envContent);
        } else {
            $envContent .= "\nADMIN_PASSWORD=" . $request->new_password;
        }
        
        File::put($envPath, $envContent);
        
        // Actualizar el config cache
        config(['app.admin_password' => $request->new_password]);
        Session::put('admin_password', $request->new_password);
        Session::forget('needs_password_change');

        return redirect()->route('admin.dashboard')->with('success', 'Contraseña actualizada correctamente');
    }

    public function dashboard()
    {
        $foodsCount = Food::count();
        $exercisesCount = Exercise::count();
        
        return view('admin.dashboard', compact('foodsCount', 'exercisesCount'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');
        
        // Manejar la imagen con sistema corregido
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imageName = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
                
                // Asegurar que el directorio público existe
                $publicStoragePath = public_path('storage');
                $foodsPath = $publicStoragePath . '/foods';
                
                if (!File::exists($publicStoragePath)) {
                    File::makeDirectory($publicStoragePath, 0755, true);
                }
                
                if (!File::exists($foodsPath)) {
                    File::makeDirectory($foodsPath, 0755, true);
                }
                
                // Mover la imagen al directorio público
                $image->move($foodsPath, $imageName);
                
                // Establecer permisos correctos
                chmod($foodsPath . '/' . $imageName, 0644);
                
                $data['image_url'] = $imageName;
                
                Log::info('Imagen guardada exitosamente: ' . $foodsPath . '/' . $imageName);
                
            } catch (\Exception $e) {
                Log::error('Error guardando imagen: ' . $e->getMessage());
                // Continuar sin imagen si hay error
            }
        }

        $data['is_active'] = true;
        $data['servings'] = $data['servings'] ?? 1;

        Food::create($data);

        return redirect()->route('admin.dashboard')->with('success', 'Comida agregada correctamente');
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');
        
        // Manejar la imagen con sistema corregido
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imageName = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
                
                // Asegurar que el directorio público existe
                $publicStoragePath = public_path('storage');
                $exercisesPath = $publicStoragePath . '/exercises';
                
                if (!File::exists($publicStoragePath)) {
                    File::makeDirectory($publicStoragePath, 0755, true);
                }
                
                if (!File::exists($exercisesPath)) {
                    File::makeDirectory($exercisesPath, 0755, true);
                }
                
                // Mover la imagen al directorio público
                $image->move($exercisesPath, $imageName);
                
                // Establecer permisos correctos
                chmod($exercisesPath . '/' . $imageName, 0644);
                
                $data['image_url'] = $imageName;
                
                Log::info('Imagen ejercicio guardada exitosamente: ' . $exercisesPath . '/' . $imageName);
                
            } catch (\Exception $e) {
                Log::error('Error guardando imagen ejercicio: ' . $e->getMessage());
                // Continuar sin imagen si hay error
            }
        }

        $data['is_active'] = true;

        Exercise::create($data);

        return redirect()->route('admin.dashboard')->with('success', 'Ejercicio agregado correctamente');
    }
}
