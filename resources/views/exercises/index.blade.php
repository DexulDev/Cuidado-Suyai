@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Ejercicios Saludables</h1>
    <div id="app">
        <div class="row">
            <div class="col-12">
                <exercise-search></exercise-search>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <exercise-list></exercise-list>
            </div>
        </div>
    </div>
</div>
@endsection