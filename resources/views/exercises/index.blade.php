@extends('layouts.app')

@section('hero')
<div class="hero-section">
    <h1>Ejercicios Saludables</h1>
    <div class="section-divider"></div>
    <p class="lead mb-0">Rutinas y actividades para mantenerte activo. Filtra, descubre y visualiza detalles de cada ejercicio.</p>
</div>
@endsection

@section('content')
<div id="app" class="d-flex flex-column gap-4">
    <search-container></search-container>
</div>
@endsection