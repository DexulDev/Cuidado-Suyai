@extends('layouts.app')

@section('content')
<div class="py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 fw-bold m-0" style="color:var(--cn-primary)">Nueva Comida</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Volver</a>
  </div>
  <div class="card-modern p-4">
    <form method="POST" action="{{ route('admin.foods.store') }}" enctype="multipart/form-data" class="row g-4">
      @csrf
      <div class="col-md-6">
        <label class="form-label small fw-semibold">Nombre *</label>
        <input type="text" name="name" value="{{ old('name') }}" required class="form-control" />
        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Categoría *</label>
        <select name="category" class="form-select" required>
          <option value="">Seleccionar</option>
          @foreach(['desayuno','almuerzo','cena','snack','postre'] as $cat)
            <option value="{{ $cat }}" @selected(old('category')===$cat)>{{ ucfirst($cat) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Dificultad *</label>
        <select name="difficulty" class="form-select" required>
          @foreach(['fácil','intermedio','difícil'] as $dif)
            <option value="{{ $dif }}" @selected(old('difficulty')===$dif)>{{ ucfirst($dif) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Prep. (min)</label>
        <input type="number" name="preparation_time" value="{{ old('preparation_time') }}" class="form-control" />
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Calorías</label>
        <input type="number" name="calories_per_serving" value="{{ old('calories_per_serving') }}" class="form-control" />
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-semibold">Proteína (g)</label>
        <input type="number" name="protein" value="{{ old('protein') }}" class="form-control" />
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-semibold">Carb. (g)</label>
        <input type="number" name="carbohydrates" value="{{ old('carbohydrates') }}" class="form-control" />
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-semibold">Grasas (g)</label>
        <input type="number" name="fats" value="{{ old('fats') }}" class="form-control" />
      </div>
      <div class="col-12">
        <label class="form-label small fw-semibold">Descripción *</label>
        <textarea name="description" rows="3" required class="form-control">{{ old('description') }}</textarea>
      </div>
      <div class="col-12">
        <label class="form-label small fw-semibold">Ingredientes *</label>
        <textarea name="ingredients" rows="3" required class="form-control" placeholder="Un ingrediente por línea">{{ old('ingredients') }}</textarea>
      </div>
      <div class="col-12">
        <label class="form-label small fw-semibold">Preparación</label>
        <textarea name="preparation" rows="4" class="form-control" placeholder="Pasos separados por línea">{{ old('preparation') }}</textarea>
      </div>
      <div class="col-12">
        <label class="form-label small fw-semibold">Imágenes (múltiples)</label>
        <input type="file" name="images[]" multiple accept="image/*" class="form-control" />
        <div class="form-text">JPG/PNG/GIF máx 5MB c/u</div>
      </div>
      <div class="col-12 d-flex justify-content-end gap-2 mt-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Cancelar</a>
        <button class="btn btn-cn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>
@endsection