@extends('layouts.app')

@section('title', 'Comparación de Categorías')

@section('content')
<div class="container-fluid px-4">
    <h1 class="fw-bold mb-4">Comparación de Categorías</h1>

    <!-- Formulario para seleccionar categorías -->
    <form method="GET" action="{{ route('admin.categories.compare') }}" class="row g-3 mb-4">
        <div class="col-md-5">
            <label for="category_a" class="form-label">Categoría A</label>
            <select name="category_a" id="category_a" class="form-control">
                <option value="">Selecciona una categoría</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_a') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-5">
            <label for="category_b" class="form-label">Categoría B</label>
            <select name="category_b" id="category_b" class="form-control">
                <option value="">Selecciona una categoría</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_b') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Comparar</button>
        </div>
    </form>

    <!-- Mostrar errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if (isset($result))
        <!-- Resultados de la comparación -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Resultados de la Comparación</h5>
            </div>
            <div class="card-body">
                <p class="fs-5">{{ $result }}</p>
                <ul>
                    <li><strong>{{ $categoryA->name }}:</strong> {{ $totalA }} ventas</li>
                    <li><strong>{{ $categoryB->name }}:</strong> {{ $totalB }} ventas</li>
                </ul>
            </div>
        </div>
    @endif
</div>
@endsection
