<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña - Admin</title>
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
            <h1 class="text-2xl font-bold mb-2" style="color: var(--cn-primary);">Cambiar Contraseña</h1>
            <p class="text-gray-600">Es necesario cambiar la contraseña por defecto</p>
        </div>

        <form method="POST" action="{{ route('admin.update-password') }}">
            @csrf
            
            <div class="mb-4">
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                <input type="password" 
                       id="new_password" 
                       name="new_password" 
                       required
                       minlength="8"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:border-transparent">
                @error('new_password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña</label>
                <input type="password" 
                       id="new_password_confirmation" 
                       name="new_password_confirmation" 
                       required
                       minlength="8"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:border-transparent">
            </div>

            <button type="submit" 
                    class="w-full py-2 px-4 rounded-md transition duration-200 font-medium"
                    style="background-color: var(--cn-accent); color: var(--cn-dark);"
                    onmouseover="this.style.backgroundColor='#e6ac00'"
                    onmouseout="this.style.backgroundColor='var(--cn-accent)'">
                Actualizar Contraseña
            </button>
        </form>
    </div>
</body>
</html>