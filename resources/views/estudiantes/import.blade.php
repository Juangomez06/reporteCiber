@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Importar Estudiantes desde Excel</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('estudiantes.importar.post') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="archivo">Archivo Excel</label>
            <input type="file" name="archivo" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Importar</button>
    </form>
</div>
@endsection
