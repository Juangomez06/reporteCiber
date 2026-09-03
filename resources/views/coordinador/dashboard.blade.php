@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Panel del Coordinador</h1>
    <p>Bienvenido, {{ auth()->user()->name }}. Tienes rol de coordinador.</p>
    <a href="{{ route('estudiantes.importar') }}" class="btn btn-primary">Importar Estudiantes</a>
</div>
@endsection
