<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Cuidado Suyai</title>
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
<body class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold mb-2" style="color: var(--cn-primary);">Admin Login</h1>
            <p class="text-gray-600">Cuidado Suyai - Panel de Administración</p>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.authenticate') }}">
            @csrf
            
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:border-transparent">
            </div>

            <button type="submit" 
                    class="w-full py-2 px-4 rounded-md transition duration-200 font-medium"
                    style="background-color: var(--cn-accent); color: var(--cn-dark);"
                    onmouseover="this.style.backgroundColor='#e6ac00'"
                    onmouseout="this.style.backgroundColor='var(--cn-accent)'">
                Iniciar Sesión
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('foods.index') }}" class="text-sm text-gray-600 hover:underline">
                ← Volver al sitio principal
            </a>
        </div>
    </div>
</body>
</html>