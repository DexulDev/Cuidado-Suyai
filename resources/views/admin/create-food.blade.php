<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Comida - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --cn-primary: #8B0000;
            --cn-secondary: #A52A2A;
            --cn-accent: #FFC107;
            --cn-light: #F5DEB3;
            --cn-dark: #000000;
            --cn-white: #FFFFFF;
        }
        
        body {
            background: linear-gradient(to right, #e9cd98 0%, transparent 5%), linear-gradient(to left, #e9cd98 0%, transparent 5%), #f5deb3 !important;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="p-4" style="background-color: var(--cn-primary);">
        <div class="container mx-auto flex justify-between items-center text-white">
            <h1 class="text-xl font-bold">Agregar Nueva Comida</h1>
            <a href="{{ route('admin.dashboard') }}" class="hover:underline">← Volver al Dashboard</a>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.foods.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2"
                               style="focus:ring-color: var(--cn-primary);">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                        <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                            <option value="">Seleccionar categoría</option>
                            <option value="desayuno" {{ old('category') == 'desayuno' ? 'selected' : '' }}>Desayuno</option>
                            <option value="almuerzo" {{ old('category') == 'almuerzo' ? 'selected' : '' }}>Almuerzo</option>
                            <option value="cena" {{ old('category') == 'cena' ? 'selected' : '' }}>Cena</option>
                            <option value="snack" {{ old('category') == 'snack' ? 'selected' : '' }}>Snack</option>
                            <option value="postre" {{ old('category') == 'postre' ? 'selected' : '' }}>Postre</option>
                        </select>
                        @error('category')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dificultad *</label>
                        <select name="difficulty" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                            <option value="">Seleccionar dificultad</option>
                            <option value="fácil" {{ old('difficulty') == 'fácil' ? 'selected' : '' }}>Fácil</option>
                            <option value="intermedio" {{ old('difficulty') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                            <option value="difícil" {{ old('difficulty') == 'difícil' ? 'selected' : '' }}>Difícil</option>
                        </select>
                        @error('difficulty')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tiempo de preparación (min)</label>
                        <input type="number" name="preparation_time" value="{{ old('preparation_time') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                        @error('preparation_time')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Calorías por porción</label>
                        <input type="number" name="calories_per_serving" value="{{ old('calories_per_serving') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                        @error('calories_per_serving')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proteínas (g)</label>
                        <input type="number" name="protein" value="{{ old('protein') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                        @error('protein')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Carbohidratos (g)</label>
                        <input type="number" name="carbohydrates" value="{{ old('carbohydrates') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                        @error('carbohydrates')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grasas (g)</label>
                        <input type="number" name="fats" value="{{ old('fats') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                        @error('fats')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imagen</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                    <p class="text-sm text-gray-500 mt-1">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</p>
                    @error('image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                    <textarea name="description" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2"
                              placeholder="Describe la comida...">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ingredientes *</label>
                    <textarea name="ingredients" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2"
                              placeholder="Lista los ingredientes necesarios...">{{ old('ingredients') }}</textarea>
                    @error('ingredients')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preparación</label>
                    <textarea name="preparation" rows="6"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2"
                              placeholder="Pasos para preparar la comida...">{{ old('preparation') }}</textarea>
                    @error('preparation')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 rounded-md"
                            style="background-color: var(--cn-primary); color: white;"
                            onmouseover="this.style.backgroundColor='#660000'"
                            onmouseout="this.style.backgroundColor='var(--cn-primary)'">
                        Guardar Comida
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>