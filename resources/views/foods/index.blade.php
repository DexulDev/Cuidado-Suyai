@extends('layouts.app')

@section('hero')
<div class="hero-section">
    <h1>Bienestar Diario</h1>
    <div class="section-divider"></div>
    <p class="lead mb-0">Explora recetas nutritivas y ejercicios saludables para mejorar tu calidad de vida.</p>
</div>
@endsection

@section('content')
<div id="app">
    <search-container></search-container>
</div>
@endsection