@extends('layouts.app')

@section('content')
<div class="py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 fw-bold m-0" style="color:var(--cn-primary)">Nuevo Ejercicio</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Volver</a>
  </div>
  <div class="card-modern p-4">
    <form method="POST" action="{{ route('admin.exercises.store') }}" enctype="multipart/form-data" class="row g-4">
      @csrf
      <div class="col-md-6">
        <label class="form-label small fw-semibold">Nombre *</label>
        <input type="text" name="name" required value="{{ old('name') }}" class="form-control" />
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Categoría *</label>
        <select name="category" required class="form-select">
          @foreach(['cardio','fuerza','flexibilidad','resistencia','equilibrio'] as $cat)
            <option value="{{ $cat }}" @selected(old('category')===$cat)>{{ ucfirst($cat) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Grupo Muscular</label>
        <select name="muscle_group" class="form-select">
          <option value="">Ninguno</option>
          @foreach(['piernas','brazos','pecho','espalda','abdomen','todo el cuerpo'] as $mg)
            <option value="{{ $mg }}" @selected(old('muscle_group')===$mg)>{{ ucfirst($mg) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Dificultad *</label>
        <select name="difficulty" required class="form-select">
          @foreach(['principiante','intermedio','avanzado'] as $dif)
            <option value="{{ $dif }}" @selected(old('difficulty')===$dif)>{{ ucfirst($dif) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Duración (min)</label>
        <input type="number" name="duration" value="{{ old('duration') }}" class="form-control" />
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Calorías</label>
        <input type="number" name="calories_burned" value="{{ old('calories_burned') }}" class="form-control" />
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Intensidad</label>
        <select name="intensity" class="form-select">
          @foreach(['baja','moderada','alta','muy alta'] as $int)
            <option value="{{ $int }}" @selected(old('intensity')===$int)>{{ ucfirst($int) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Equipamiento</label>
        <input type="text" name="equipment" value="{{ old('equipment') }}" class="form-control" placeholder="Ej: Mancuernas" />
      </div>
      <div class="col-md-6">
        <label class="form-label small fw-semibold">Imágenes</label>
        <input type="file" name="images[]" multiple accept="image/*" class="form-control" />
        <div class="form-text">JPG/PNG/GIF máx 5MB c/u</div>
      </div>
      <div class="col-12">
        <label class="form-label small fw-semibold">Descripción *</label>
        <textarea name="description" rows="5" required class="form-control" placeholder="Instrucciones y recomendaciones...">{{ old('description') }}</textarea>
      </div>
      <div class="col-12 d-flex justify-content-end gap-2 mt-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Cancelar</a>
        <button class="btn btn-cn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>
@endsection