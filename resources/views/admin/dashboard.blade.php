<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Cuidado Suyai</title>
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
            <h1 class="text-xl font-bold">Panel de Administración - Cuidado Suyai</h1>
            <div class="flex items-center space-x-4">
                @if(Session::get('needs_password_change'))
                    <span class="px-2 py-1 rounded text-sm" style="background-color: var(--cn-accent); color: var(--cn-dark);">
                        Cambiar contraseña requerido
                    </span>
                @endif
                <a href="{{ route('admin.logout') }}" class="hover:underline">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Comidas</h3>
                <p class="text-3xl font-bold" style="color: var(--cn-primary);">{{ $foodsCount }}</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Ejercicios</h3>
                <p class="text-3xl font-bold" style="color: var(--cn-secondary);">{{ $exercisesCount }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Búsquedas Hoy</h3>
                <p class="text-3xl font-bold" style="color: var(--cn-accent);">{{ App\Models\Search::whereDate('created_at', today())->count() }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Búsquedas</h3>
                <p class="text-3xl font-bold" style="color: var(--cn-secondary);">{{ App\Models\Search::count() }}</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Gestión de Comidas</h2>
                <p class="text-gray-600 mb-4">Agregar nuevas recetas y comidas saludables</p>
                <a href="{{ route('admin.foods.create') }}" 
                   class="inline-block px-4 py-2 rounded transition"
                   style="background-color: var(--cn-primary); color: white;"
                   onmouseover="this.style.backgroundColor='#660000'"
                   onmouseout="this.style.backgroundColor='var(--cn-primary)'">
                    Agregar Nueva Comida
                </a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Gestión de Ejercicios</h2>
                <p class="text-gray-600 mb-4">Agregar nuevos ejercicios y rutinas</p>
                <a href="{{ route('admin.exercises.create') }}" 
                   class="inline-block px-4 py-2 rounded transition"
                   style="background-color: var(--cn-secondary); color: white;"
                   onmouseover="this.style.backgroundColor='#8B1538'"
                   onmouseout="this.style.backgroundColor='var(--cn-secondary)'">
                    Agregar Nuevo Ejercicio
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Búsquedas Populares</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold mb-2">Comidas más buscadas:</h3>
                    <ul class="space-y-1">
                        @foreach(App\Models\Search::getPopularTerms('food', 5) as $search)
                            <li class="text-gray-600">{{ $search->query }} ({{ $search->count }} búsquedas)</li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Ejercicios más buscados:</h3>
                    <ul class="space-y-1">
                        @foreach(App\Models\Search::getPopularTerms('exercise', 5) as $search)
                            <li class="text-gray-600">{{ $search->query }} ({{ $search->count }} búsquedas)</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>