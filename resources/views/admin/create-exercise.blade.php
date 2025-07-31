<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Ejercicio - Admin</title>
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
    <nav class="p-4" style="background-color: var(--cn-secondary);">
        <div class="container mx-auto flex justify-between items-center text-white">
            <h1 class="text-xl font-bold">Agregar Nuevo Ejercicio</h1>
            <a href="{{ route('admin.dashboard') }}" class="hover:underline">← Volver al Dashboard</a>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.exercises.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                        <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                            <option value="">Seleccionar categoría</option>
                            <option value="cardio" {{ old('category') == 'cardio' ? 'selected' : '' }}>Cardio</option>
                            <option value="fuerza" {{ old('category') == 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                            <option value="flexibilidad" {{ old('category') == 'flexibilidad' ? 'selected' : '' }}>Flexibilidad</option>
                            <option value="resistencia" {{ old('category') == 'resistencia' ? 'selected' : '' }}>Resistencia</option>
                            <option value="equilibrio" {{ old('category') == 'equilibrio' ? 'selected' : '' }}>Equilibrio</option>
                        </select>
                        @error('category')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grupo Muscular</label>
                        <select name="muscle_group" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                            <option value="">Seleccionar grupo</option>
                            <option value="piernas" {{ old('muscle_group') == 'piernas' ? 'selected' : '' }}>Piernas</option>
                            <option value="brazos" {{ old('muscle_group') == 'brazos' ? 'selected' : '' }}>Brazos</option>
                            <option value="pecho" {{ old('muscle_group') == 'pecho' ? 'selected' : '' }}>Pecho</option>
                            <option value="espalda" {{ old('muscle_group') == 'espalda' ? 'selected' : '' }}>Espalda</option>
                            <option value="abdomen" {{ old('muscle_group') == 'abdomen' ? 'selected' : '' }}>Abdomen</option>
                            <option value="todo el cuerpo" {{ old('muscle_group') == 'todo el cuerpo' ? 'selected' : '' }}>Todo el cuerpo</option>
                        </select>
                        @error('muscle_group')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dificultad *</label>
                        <select name="difficulty" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                            <option value="">Seleccionar dificultad</option>
                            <option value="principiante" {{ old('difficulty') == 'principiante' ? 'selected' : '' }}>Principiante</option>
                            <option value="intermedio" {{ old('difficulty') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                            <option value="avanzado" {{ old('difficulty') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                        </select>
                        @error('difficulty')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duración (min)</label>
                        <input type="number" name="duration" value="{{ old('duration') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                        @error('duration')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Calorías quemadas</label>
                        <input type="number" name="calories_burned" value="{{ old('calories_burned') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                        @error('calories_burned')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Intensidad</label>
                        <select name="intensity" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                            <option value="">Seleccionar intensidad</option>
                            <option value="baja" {{ old('intensity') == 'baja' ? 'selected' : '' }}>Baja</option>
                            <option value="moderada" {{ old('intensity') == 'moderada' ? 'selected' : '' }}>Moderada</option>
                            <option value="alta" {{ old('intensity') == 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="muy alta" {{ old('intensity') == 'muy alta' ? 'selected' : '' }}>Muy Alta</option>
                        </select>
                        @error('intensity')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Equipamiento</label>
                        <input type="text" name="equipment" value="{{ old('equipment') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2"
                               placeholder="Ej: Ninguno, Mancuernas, etc.">
                        @error('equipment')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imagen del ejercicio</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                    <p class="text-sm text-gray-500 mt-1">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</p>
                    @error('image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                    <textarea name="description" rows="6" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2"
                              placeholder="Describe cómo realizar el ejercicio, instrucciones paso a paso...">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 rounded-md"
                            style="background-color: var(--cn-secondary); color: white;"
                            onmouseover="this.style.backgroundColor='#8B1538'"
                            onmouseout="this.style.backgroundColor='var(--cn-secondary)'">
                        Guardar Ejercicio
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>