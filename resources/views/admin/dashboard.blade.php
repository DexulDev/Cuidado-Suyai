@extends('layouts.app')

@section('content')
<!-- Header y título de página -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold m-0" style="color:var(--cn-primary)">Panel de Administración</h1>
    <div class="d-flex align-items-center gap-3">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#searchAnalyticsModal">
            <i class="bi bi-graph-up"></i> Telemetría de búsquedas <span class="badge bg-light text-dark">{{ $searchesToday }} hoy</span>
        </button>
        
        @if(Session::get('needs_password_change'))
            <span class="badge text-bg-warning text-dark">Cambiar contraseña requerido</span>
        @endif
        <form action="{{ route('admin.logout') }}" method="POST" onsubmit="return confirm('¿Cerrar sesión?')">
            @csrf
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</button>
        </form>
    </div>
</div>

<!-- Modal para telemetría de búsquedas -->
<div class="modal fade admin-modal" id="searchAnalyticsModal" tabindex="-1" aria-labelledby="searchAnalyticsModalLabel" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content glass border-0">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title" id="searchAnalyticsModalLabel">
                    <i class="bi bi-search-heart me-2"></i>Telemetría de Búsquedas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="search-telemetry-container p-3">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="stats-card p-3 text-center shadow-sm rounded">
                                <h5 class="mb-1">Total Búsquedas</h5>
                                <div id="totalSearchesCount" class="fs-2 fw-bold text-primary">{{ $searchesCount }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card p-3 text-center shadow-sm rounded">
                                <h5 class="mb-1">Búsquedas Hoy</h5>
                                <div id="todaySearchesCount" class="fs-2 fw-bold text-success">{{ $searchesToday }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-card p-3 shadow-sm rounded">
                                <h5 class="mb-2">Filtros</h5>
                                <div class="d-flex gap-2">
                                    <button onclick="filterSearches('all')" class="btn btn-sm btn-outline-primary active">Todas</button>
                                    <button onclick="filterSearches('food')" class="btn btn-sm btn-outline-primary">Alimentos</button>
                                    <button onclick="filterSearches('exercise')" class="btn btn-sm btn-outline-primary">Ejercicios</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-modern mb-4">
                        <div class="table-responsive search-table-container">
                            <table class="table table-hover align-middle mb-0" id="searchesTable">
                                <thead class="small">
                                    <tr>
                                        <th>TIPO</th>
                                        <th>BÚSQUEDA</th>
                                        <th>CATEGORÍA</th>
                                        <th>RESULTADOS</th>
                                        <th>FECHA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Aplicación Vue -->
<div id="app" class="content-fade">
    @php
        $adminRoutes = [
            'createFood' => route('admin.foods.create'),
            'createExercise' => route('admin.exercises.create'),
            'updateFood' => url('/admin/foods/:id'),
            'destroyFood' => url('/admin/foods/:id'),
            'updateExercise' => url('/admin/exercises/:id'),
            'searchAnalyticsJson' => route('admin.api.searches'),
        ];
    @endphp

    <admin-dashboard 
        :initial-foods='@json($foods)'
        :initial-exercises='@json($exercises)'
        :routes='@json($adminRoutes)'
        csrf='{{ csrf_token() }}'
    ></admin-dashboard>
</div>
@endsection

@push('scripts')
<script>
    // Variables globales
    let currentSearchData = [];
    let currentFilter = 'all';
    window.currentSearchData = [];
    window.currentFilter = 'all';
    
    // Hacer las funciones globales desde el principio
    window.filterSearches = function(filter) {
        window.currentFilter = filter;
        
        // Actualizar botones activos
        document.querySelectorAll('.stats-card .btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Encontrar el botón clickeado y marcarlo como activo
        const clickedButton = document.querySelector(`[onclick="filterSearches('${filter}')"]`);
        if (clickedButton) {
            clickedButton.classList.add('active');
        }
        
        showFilteredSearches();
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Cuando se abre el modal de búsquedas, cargar los datos
        const searchModal = document.getElementById('searchAnalyticsModal');
        if (searchModal) {
            searchModal.addEventListener('shown.bs.modal', function() {
                loadSearchAnalytics();
            });
        }
        
        // También podemos cargar un preview inicial
        loadSearchAnalyticsPreview();
    });
    
    async function loadSearchAnalytics() {
        try {
            const response = await fetch('{{ route("admin.api.searches") }}');
            const data = await response.json();
            
            window.currentSearchData = data.searches.data;
            currentSearchData = data.searches.data;
            
            // Actualizar las estadísticas
            document.getElementById('totalSearchesCount').textContent = data.stats.total;
            document.getElementById('todaySearchesCount').textContent = data.stats.today;
            
            // Mostrar datos filtrados
            showFilteredSearches();
            
        } catch (error) {
            console.error('Error cargando datos de búsquedas:', error);
            document.querySelector('#searchesTable tbody').innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="alert alert-danger">Error al cargar datos. Intente nuevamente.</div>
                    </td>
                </tr>
            `;
        }
    }
    
    function loadSearchAnalyticsPreview() {
        // Cargar solo los datos básicos para mostrar en el botón
        fetch('{{ route("admin.api.searches") }}')
            .then(response => response.json())
            .then(data => {
                const todayCount = document.querySelector('.badge.bg-light.text-dark');
                if (todayCount) {
                    todayCount.textContent = data.stats.today + ' hoy';
                }
            })
            .catch(error => console.error('Error cargando preview de búsquedas:', error));
    }
    
    function showFilteredSearches() {
        const tableBody = document.querySelector('#searchesTable tbody');
        
        // Usar la variable global o local
        const dataToUse = window.currentSearchData || currentSearchData;
        
        if (!dataToUse || dataToUse.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="alert alert-info">No se encontraron datos de búsqueda.</div>
                    </td>
                </tr>
            `;
            return;
        }
        
        // Filtrar datos según el filtro activo
        let filteredData = dataToUse;
        if (window.currentFilter !== 'all') {
            filteredData = dataToUse.filter(item => item.search_type === window.currentFilter);
        }
        
        // Construir filas de la tabla
        let html = '';
        
        filteredData.forEach(search => {
            const badgeClass = search.search_type === 'food' ? 'bg-success' : 'bg-primary';
            const searchLabel = search.search_type === 'food' ? 'Alimento' : 'Ejercicio';
            const date = new Date(search.created_at);
            const formattedDate = date.toLocaleString();
            
            html += `
                <tr>
                    <td><span class="badge ${badgeClass}">${searchLabel}</span></td>
                    <td>${search.query || '<em>Sin texto</em>'}</td>
                    <td>${search.category || '-'}</td>
                    <td>${search.results_count}</td>
                    <td class="small">${formattedDate}</td>
                </tr>
            `;
        });
        
        if (html === '') {
            html = `
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="alert alert-info">No hay búsquedas con el filtro actual.</div>
                    </td>
                </tr>
            `;
        }
        
        tableBody.innerHTML = html;
    }
</script>
@endpush