@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Panel del Estudiante</h1>
    <p>Bienvenido, {{ auth()->user()->name }}. Tienes rol de estudiante.</p>
</div>
@endsection
