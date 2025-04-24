@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Recetas Saludables</h1>
    <div id="app">
        <div class="row">
            <div class="col-12">
                <food-search></food-search>
            </div>
        </div>
    </div>
</div>
@endsection