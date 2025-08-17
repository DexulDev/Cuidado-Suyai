<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Bienestar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="https://kidstudia.cl/wp-content/uploads/2022/11/logo-colegio-liahona.jpg">
    <link rel="apple-touch-icon" href="https://kidstudia.cl/wp-content/uploads/2022/11/logo-colegio-liahona.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    @vite(['resources/css/app.css','resources/css/footer.css','resources/css/layout-fixes.css','resources/css/header-footer-fix.css','resources/css/modal-fixes.css','resources/css/cross-page.css','resources/js/app.js'])
    @stack('styles')
    <style>
        :root { scroll-behavior: smooth; }
        body { 
            font-family: var(--cn-font-body);
            background: radial-gradient(circle at 20% 20%, rgba(245,222,179,.4), rgba(211,191,154,.25) 40%, rgba(255,255,255,.95));
            min-height:100vh;
            display:flex;
            flex-direction:column;
            position: relative;
            color: var(--cn-dark);
            overflow-x: hidden; /* Prevent horizontal scrolling issues */
            background-attachment: fixed; /* Mantener fijo el fondo */
        }
        
        .main-content {
            flex-grow: 1;
            z-index: 1; /* Lower z-index than header and footer */
            position: relative; /* Required for z-index to work */
        }
        .app-shell-header {
            backdrop-filter: blur(20px) saturate(180%);
            background: #872727 !important; /* Fixed wine color */
            background-color: #872727 !important; /* Fixed wine color - redundancia intencional */
            background-image: none !important; /* Eliminar cualquier gradiente o imagen */
            color: white !important; /* Ensure text is white */
            box-shadow: 0 8px 32px -12px rgba(0,0,0,.35);
            border-bottom: 1px solid rgba(255,255,255,.2);
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 100; /* Nivel normal para header */
            transition: var(--cn-transition);
            padding: .6rem 0;
        }
        .app-shell-header * {
            color: white !important; /* Force all text in header to be white */
        }
        .brand-title {
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .brand-title i {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,.2));
        }
        .nav-soft-btn {
            background: var(--cn-gradient-accent);
            color: var(--cn-dark);
            padding: .55rem 1.1rem;
            border-radius: var(--cn-radius-sm);
            font-weight: 600;
            box-shadow: 0 4px 18px -6px rgba(0,0,0,.4);
            transition: var(--cn-transition);
            transform-origin: center;
        }
        .nav-soft-btn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 20px -4px rgba(0,0,0,.45);
        }
        .nav-link-clean {
            font-weight: 500;
            letter-spacing: .25px;
            padding: .55rem 1rem;
            border-radius: var(--cn-radius-sm);
            transition: var(--cn-transition);
            color: var(--cn-white) !important;
            opacity: .85;
            position: relative;
        }
        .nav-link-clean:hover, .nav-link-clean.active {
            opacity: 1;
            background: rgba(255,255,255,.15);
            transform: translateY(-1px);
        }
        .nav-link-clean.active::after {
            content: '';
            position: absolute;
            bottom: 6px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: var(--cn-accent);
            border-radius: 10px;
        }
        main.main-content {
            flex: 1 0 auto; /* Flex grow but don't shrink */
            width: 100%;
            padding-top: 2rem;
            padding-bottom: 6rem; /* Add more space for footer */
            position: relative;
            z-index: 10;
            min-height: 60vh; /* Minimum height for main content */
        }
        
/* Footer Styles */
        .footer-modern {
            background: #4d1d1d !important; /* Dark wine color */
            background-color: #4d1d1d !important; /* Dark wine - redundancia intencional */
            background-image: none !important; /* Eliminar cualquier gradiente o imagen */
            color: white !important; /* Light text for contrast */
            padding: 1.5rem 0;
            position: relative;
            width: 100%;
            z-index: 90; /* Nivel normal para footer */
        }
        .footer-modern * {
            color: rgba(255,255,255,0.9) !important; /* Ensure all text in footer is light */
        }        
        .footer-modern .container {
            max-width: 1140px;
            color: white !important; /* Ensure all text is white in container */
        }
        
        .footer-modern small {
            font-weight: 500;
            letter-spacing: .4px;
            color: white !important; /* Ensure small text is white */
        }
        
        /* Badge and UI Elements */
        .pill-badge {
            background: rgba(255,255,255,.22);
            color: #fff;
            padding: .35rem .85rem;
            font-size: .7rem;
            border-radius: 50rem;
            letter-spacing: .6px;
            text-transform: uppercase;
            box-shadow: 0 2px 10px -4px rgba(0,0,0,.3);
        }
        
        /* Hero Section */
        .hero-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            padding: 1.5rem 0 1.5rem;
        }
        
        .hero-section h1 {
            font-size: clamp(1.8rem, 2.3rem + .8vw, 3.2rem);
            background: linear-gradient(90deg, var(--cn-primary), var(--cn-secondary));
            -webkit-background-clip: text;
            color: transparent;
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -0.5px;
        }
        
        .hero-section p.lead {
            font-weight: 400;
            font-size: 1.1rem;
            max-width: 820px;
            opacity: 0.9;
        }
        
        /* Card and Container Styles */
        .glass {
            backdrop-filter: blur(16px) saturate(160%);
            background: rgba(255,255,255,.8);
            border: 1px solid rgba(255,255,255,.6);
            border-radius: var(--cn-radius);
            box-shadow: 0 12px 32px -10px rgba(0,0,0,.16);
            transition: var(--cn-transition);
        }
        
        .glass:hover {
            box-shadow: 0 16px 36px -8px rgba(0,0,0,.2);
            transform: translateY(-3px);
        }
        
        .section-divider {
            height: 4px;
            width: 70px;
            background: var(--cn-accent);
            border-radius: 2px;
            margin: 0.5rem 0 1.5rem;
        }
        
        /* Animations and Transitions */
        .content-fade {
            animation: fadeIn .7s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .link-icon {
            transition: var(--cn-transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 36px;
            width: 36px;
            border-radius: 50%;
            background: rgba(var(--cn-primary-rgb), 0.1);
        }
        
        .link-icon:hover {
            transform: translateY(-3px);
            background: rgba(var(--cn-primary-rgb), 0.15);
            box-shadow: 0 4px 12px -2px rgba(var(--cn-primary-rgb), 0.2);
        }
        
        .link-icon-footer {
            transition: var(--cn-transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 36px;
            width: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            color: #f5deb3 !important; /* Cream color */
        }
        
        .link-icon-footer i {
            font-size: 1.25rem;
            color: #f5deb3 !important; /* Cream color */
        }
        
        .link-icon-footer:hover {
            transform: translateY(-3px);
            background: var(--cn-accent);
            box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.3);
        }
        
        .link-icon-footer:hover i {
            color: var(--cn-dark) !important;
        }
        
        /* Toolbar and Button Styles */
        .toolbar-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }
        
        .toolbar-actions .btn {
            border-radius: var(--cn-radius-sm);
            font-weight: 500;
            padding: .5rem 1.2rem;
            transition: var(--cn-transition);
        }
        
        .btn-primary {
            background: var(--cn-primary);
            border-color: var(--cn-primary);
            box-shadow: 0 4px 12px -4px rgba(var(--cn-primary-rgb), 0.4);
        }
        
        .btn-primary:hover {
            background: var(--cn-primary);
            filter: brightness(1.1);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px -3px rgba(var(--cn-primary-rgb), 0.5);
        }
        
        .shadow-soft {
            box-shadow: 0 8px 30px -8px rgba(0,0,0,.18);
        }
        
        /* Search Component */
        .search-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .search-wrapper input {
            padding: .65rem 1rem .65rem 2.6rem;
            border-radius: var(--cn-radius) !important;
            border-color: rgba(0,0,0,.1);
            box-shadow: var(--cn-shadow-sm);
            transition: var(--cn-transition);
        }
        
        .search-wrapper input:focus {
            box-shadow: 0 3px 15px -3px rgba(var(--cn-primary-rgb), 0.25);
            border-color: rgba(var(--cn-primary-rgb), 0.3);
        }
        
        .search-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--cn-secondary);
        }
        
        /* School Logo */
        .school-logo {
            height: 40px;
            width: auto;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transition: var(--cn-transition);
        }
        
        .school-logo-large {
            height: 100px;
            width: auto;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transition: var(--cn-transition);
        }
        
        .school-logo:hover, .school-logo-large:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .brand-title { 
                font-size: 1.05rem; 
            }
            .hero-section h1 { 
                font-size: 1.8rem; 
            }
            .school-logo {
                height: 32px;
            }
            .school-logo-large {
                height: 40px;
            }
            .navbar-collapse {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--cn-primary);
                padding: 1rem;
                box-shadow: 0 10px 20px -5px rgba(0,0,0,.3);
                border-top: 1px solid rgba(255,255,255,.1);
            }
            .navbar-collapse ul {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }
            .navbar-collapse .nav-item {
                width: 100%;
            }
            .navbar-collapse .nav-link-clean {
                width: 100%;
                display: block;
                padding: .75rem 1rem;
            }
            .navbar-collapse .nav-soft-btn {
                margin-top: .5rem;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
</head>
<body class="@yield('body-class', '')">
    <header class="app-shell-header">
        <div class="container">
            <nav class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3 brand-title">
                    <a href="{{ route('foods.index') }}" class="d-flex align-items-center text-decoration-none">
                        <img src="https://www.colegiosliahonaepv.cl/wp-content/uploads/2021/11/0000-copia-3.jpg" alt="Colegio Liahona" class="school-logo-large">
                    </a>
                </div>
                
                <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list text-white fs-4"></i>
                </button>
                
                <div class="collapse navbar-collapse d-md-flex justify-content-end" id="navbarContent">
                    <ul class="nav align-items-center gap-2">
                        <li class="nav-item">
                            <a href="{{ route('foods.index') }}" class="nav-link nav-link-clean {{ request()->is('inicio') || request()->is('/') ? 'active' : '' }}">
                                <i class="bi bi-journal-richtext me-1 d-md-none d-inline-block"></i>Inicio
                            </a>
                        </li>
                        <li class="nav-item ms-md-2">
                            <a href="{{ route('admin.login') }}" class="nav-soft-btn">
                                <i class="bi bi-gear me-md-0 me-2"></i>
                                <span class="d-md-none">Administrar</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container content-fade" id="app">
            @yield('hero')
            @yield('content')
            <toasts-container></toasts-container>
        </div>
    </main>

    <footer class="footer-modern">
        <div class="container">
            <div class="row g-4 justify-content-center text-center">
                <div class="col-12">
                    <div class="mb-3">
                        <div class="d-flex justify-content-center align-items-center gap-2 brand-title mb-2">
                            <i class="bi bi-heart-pulse"></i>
                            <span>Cuidado Suyai</span>
                        </div>
                        <div class="section-divider mx-auto" style="width: 40px; margin: 12px auto; background: var(--cn-accent);"></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <a class="text-decoration-none link-icon-footer" href="https://www.facebook.com/liahonapucon/?locale=es_LA" target="_blank" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a class="text-decoration-none link-icon-footer" href="https://www.instagram.com/liahona.pucon/?hl=es" target="_blank" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a class="text-decoration-none link-icon-footer" href="https://www.colegiosliahonaepv.cl/" target="_blank" aria-label="Sitio web">
                            <i class="bi bi-globe"></i>
                        </a>
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-center small fw-semibold mb-0" style="color: white !important;">
                        Educar Para la Vida • Matias Oyarzo • Ignacio Vidal • Simon Morales
                    </p>
                    <p class="text-center small mt-1" style="color: white !important; opacity: 0.5; font-size: 0.7rem;">
                        <a href="https://github.com/DexulDev/Cuidado-Suyai" target="_blank" style="color: rgba(245,222,179,0.8) !important; text-decoration: none;">Código fuente</a>
                    </p>
                    <p class="text-center small mt-2" style="color: white !important; opacity: 0.75;">© {{ date('Y') }} Cuidado Suyai. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script adicional para garantizar que los backdrops (filtro gris) sean interactivos
        document.addEventListener('DOMContentLoaded', function() {
            // Función para arreglar los modales después de que se muestren
            document.addEventListener('shown.bs.modal', function(event) {
                // Asegurar que el backdrop tenga los estilos correctos
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                    backdrop.style.opacity = '0.5';
                    backdrop.style.pointerEvents = 'auto';
                });
                
                // Asegurar que el modal tenga eventos correctos
                const modal = event.target;
                modal.style.pointerEvents = 'auto';
                
                // SOLUCIÓN ARIA: Eliminar aria-hidden cuando el modal está visible
                modal.removeAttribute('aria-hidden');
                
                // Asegurar que el contenido sea interactivo
                const content = modal.querySelector('.modal-content');
                if (content) content.style.pointerEvents = 'auto';
                
                // Asegurar que el diálogo sea interactivo
                const dialog = modal.querySelector('.modal-dialog');
                if (dialog) dialog.style.pointerEvents = 'auto';
                
                // Reparar el botón de cierre para foco
                const closeButton = modal.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.setAttribute('tabindex', '0');
                    closeButton.setAttribute('aria-label', 'Cerrar modal');
                }
            });
        });
    </script>
</body>
</html>