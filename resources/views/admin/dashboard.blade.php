@extends('layouts.app')

@section('content')
<!-- Header y título de página -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold m-0" style="color:var(--cn-primary)">Panel de Administración</h1>
    <div class="d-flex align-items-center gap-3">
        @if(Session::get('needs_password_change'))
            <span class="badge text-bg-warning text-dark">Cambiar contraseña requerido</span>
        @endif
        <form action="{{ route('admin.logout') }}" method="POST" onsubmit="return confirm('¿Cerrar sesión?')">
            @csrf
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</button>
        </form>
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
    // Script simplificado - El modal ahora es manejado por Vue.js
    console.log('Dashboard de Admin cargado - Modal de telemetría ahora en Vue.js');
</script>
@endpush