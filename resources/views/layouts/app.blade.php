<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cuidado Newen - Motor de búsqueda de recetas y ejercicios</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        :root {
            --cn-primary: #8B0000;
            --cn-secondary: #A52A2A;
            --cn-accent: #FFC107;
            --cn-light: #F5DEB3;
            --cn-darklight: #d3bf9a;
            --cn-dark: #000000;
            --cn-white: #FFFFFF;
        }
        
        
        body {
            font-family: 'Poppins', sans-serif !important;
            background: 
                linear-gradient(to right, #e9cd98 0%, transparent 5%),
                linear-gradient(to left, #e9cd98 0%, transparent 5%),
                #f5deb3 !important;
            color: var(--cn-dark) !important;
        }
        
        .navbar-brand {
            font-weight: 600;
            color: var(--cn-primary);
        }
        
        .footer, .footer .container, .footer .container > * {
            margin-top: auto;
            padding: 1rem 0;
            background-color: var(--cn-darklight) !important;
        }
        
        .btn-primary {
            background-color: var(--cn-accent) !important;
            border-color: var(--cn-accent) !important;
            color: var(--cn-dark) !important;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: #e6ac00 !important;
            border-color: #e6ac00 !important;
            color: var(--cn-dark) !important;
        }
        
        .btn-outline-primary {
            color: var(--cn-primary) !important;
            border-color: var(--cn-primary) !important;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--cn-primary) !important;
            color: var(--cn-white) !important;
        }
        
        .logo-header, .logo-header .d-flex {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0;
            background-color: var(--cn-darklight) !important;
        }
        
        .logo-header img {
            max-height: 70px;
        }
        
        .logo-title {
            margin-left: 15px;
            text-align: left;
        }
        
        .logo-title h1 {
            margin: 0;
            color: var(--cn-primary);
            font-weight: 600;
            font-size: 1.8rem;
        }
        
        .logo-title p {
            margin: 0;
            color: var(--cn-dark);
            font-size: 0.9rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--cn-primary);
        }
        
        .badge {
            font-weight: 500;
        }
        
        .badge.bg-category {
            background-color: var(--cn-accent) !important;
            color: var(--cn-dark) !important;
        }
        
        .badge.bg-primary {
            background-color: var(--cn-primary) !important;
            color: var(--cn-white) !important;
        }
        
        .badge.bg-success {
            background-color: var(--cn-accent) !important;
            color: var(--cn-dark) !important;
        }
        
        .badge.bg-warning {
            background-color: var(--cn-secondary) !important;
            color: var(--cn-white) !important;
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: var(--cn-primary);
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--cn-primary);
            color: var(--cn-white);
        }
        
        .text-primary {
            color: var(--cn-primary) !important;
        }
        
        .border-primary {
            border-color: var(--cn-primary) !important;
        }
        
        /* Valores nutricionales */
        .nutrition-value {
            color: var(--cn-secondary);
            font-weight: bold;
        }
        
        /* Spinner para carga */
        .spinner-border {
            color: var(--cn-primary) !important;
        }
        
        /* Botones personalizados */
        .custom-btn {
            transition: all 0.3s ease;
            font-weight: 500 !important;
            padding: 0.5rem 1rem !important;
            text-decoration: none !important;
        }
        
        .custom-btn-primary {
            background-color: var(--cn-accent) !important;
            border-color: var(--cn-accent) !important;
            color: var(--cn-dark) !important;
        }
        
        .custom-btn-primary:hover {
            background-color: #e6ac00 !important;
            border-color: #e6ac00 !important;
            color: var(--cn-dark) !important;
        }
        
        .custom-btn-outline {
            background-color: transparent !important;
            border-color: var(--cn-primary) !important;
            color: var(--cn-primary) !important;
        }
        
        .custom-btn-outline:hover {
            background-color: var(--cn-primary) !important;
            color: var(--cn-white) !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="logo-header shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="{{ asset('storage/images/logo-colegio.webp') }}" alt="Logo Colegio">
                <div class="logo-title">
                    <h1>Cuidado Newen</h1>
                </div>
            </div>
            <div>
                <a href="{{ route('foods.index') }}" class="btn custom-btn {{ request()->routeIs('foods.*') ? 'custom-btn-primary' : 'custom-btn-outline' }} me-2">
                    <i class="bi bi-journal-richtext me-1"></i> Recetas
                </a>
                <a href="{{ route('exercises.index') }}" class="btn custom-btn {{ request()->routeIs('exercises.*') ? 'custom-btn-primary' : 'custom-btn-outline' }}">
                    <i class="bi bi-activity me-1"></i> Ejercicios
                </a>
            </div>
        </div>
    </div>

    <main class="container py-4">
        @yield('content')
    </main>

    <footer class="footer text-center text-muted">
        <div class="container">
            <div class="container">
                <div class="small fw-bold" style="color: var(--cn-primary);">Educar Para la Vida</div>
                
                <div class="mt-3 mb-2">
                    <a href="https://www.facebook.com/liahonapucon/?locale=es_LA" target="_blank" class="mx-2 text-decoration-none" aria-label="Facebook">
                        <i class="bi bi-facebook" style="color: var(--cn-primary); font-size: 1.5rem;"></i>
                    </a>
                    <a href="https://www.instagram.com/liahona.pucon/?hl=es" target="_blank" class="mx-2 text-decoration-none" aria-label="Instagram">
                        <i class="bi bi-instagram" style="color: var(--cn-primary); font-size: 1.5rem;"></i>
                    </a>
                    <a href="https://www.colegiosliahonaepv.cl/" target="_blank" class="mx-2 text-decoration-none" aria-label="Sitio web">
                        <i class="bi bi-globe" style="color: var(--cn-primary); font-size: 1.5rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para corregir estilos -->
    <script>
        function enforceStyles() {
            // Forzar estilos para los badges de nutrición
            document.querySelectorAll('.badge.bg-primary').forEach(badge => {
                badge.style.backgroundColor = '#8B0000';
                badge.style.color = '#FFFFFF';
            });
            
            document.querySelectorAll('.badge.bg-success').forEach(badge => {
                badge.style.backgroundColor = '#FFC107';
                badge.style.color = '#000000';
            });
            
            document.querySelectorAll('.badge.bg-warning').forEach(badge => {
                badge.style.backgroundColor = '#A52A2A';
                badge.style.color = '#FFFFFF';
            });
            
            // Forzar estilos de botones
            document.querySelectorAll('.btn-primary').forEach(btn => {
                btn.style.backgroundColor = '#FFC107';
                btn.style.borderColor = '#FFC107';
                btn.style.color = '#000000';
            });
            
            // Garantizar que los botones personalizados mantienen su estilo
            document.querySelectorAll('.custom-btn-primary').forEach(btn => {
                btn.style.backgroundColor = '#FFC107';
                btn.style.borderColor = '#FFC107';
                btn.style.color = '#000000';
            });
            
            document.querySelectorAll('.custom-btn-outline').forEach(btn => {
                btn.style.backgroundColor = 'transparent';
                btn.style.borderColor = '#8B0000';
                btn.style.color = '#8B0000';
            });
        }
        
        // Ejecutar después de cargar el DOM y al terminar de cargar
        document.addEventListener('DOMContentLoaded', enforceStyles);
        window.addEventListener('load', enforceStyles);
        
        // Observador para detectar cambios en el DOM
        const observer = new MutationObserver(enforceStyles);
        observer.observe(document.body, { childList: true, subtree: true });
        
        // Aplicar periódicamente para asegurar consistencia
        setInterval(enforceStyles, 2000);
    </script>
</body>
</html>