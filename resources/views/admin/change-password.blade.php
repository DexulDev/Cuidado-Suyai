@extends('layouts.app')

@section('title', 'Cambiar Contraseña - Admin')

@section('content')
<div>
    <password-manager 
        csrf-token="{{ csrf_token() }}"
        :is-first-time="{{ session('first_time', false) ? 'true' : 'false' }}"
        :api-errors='@json($errors->all())'
        success-message="{{ session('success', '') }}"
        update-url="{{ route('admin.update-password') }}">
    </password-manager>
</div>
@endsection