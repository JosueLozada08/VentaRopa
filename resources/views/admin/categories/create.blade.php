@extends('layouts.app')

@section('title', 'Crear Categoría')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Crear Nueva Categoría</h1>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Formulario de Categoría</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <!-- Campo Nombre -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre de la Categoría</label>
                    <input 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
