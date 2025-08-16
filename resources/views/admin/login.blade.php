@extends('layouts.app')

@push('styles')
    @vite(['resources/css/admin-login.css', 'resources/css/admin-login-fix.css'])
@endpush

@section('body-class', 'admin-login')

@section('content')
<div class="d-flex justify-content-center py-5">
    <div class="card-modern p-4" style="max-width:420px; width:100%;">
        <div class="mb-4 text-center">
            <h1 class="h4 fw-bold mb-1" style="color:var(--cn-primary)">Acceso Administrador</h1>
            <p class="text-muted small mb-0">Cuidado Suyai • Panel</p>
        </div>
        @if($errors->any())
            <div class="alert-modern alert-danger mb-3 small">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif
        @if(session('error'))
            <div class="alert-modern alert-danger mb-3 small">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.authenticate') }}" class="d-flex flex-column gap-3">
            @csrf
            <div>
                <label class="form-label small fw-semibold">Contraseña</label>
                <input type="password" name="password" class="form-control" required autofocus>
            </div>
            <button class="btn btn-cn-accent w-100 fw-semibold">Ingresar</button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('foods.index') }}" class="small text-decoration-none">← Volver al sitio</a>
        </div>
    </div>
</div>
@endsection