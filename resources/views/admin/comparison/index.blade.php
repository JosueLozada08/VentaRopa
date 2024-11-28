@extends('layouts.app')

@section('title', 'Comparación de Categorías')

@section('content')
<div class="container-fluid px-4">
    <h1 class="fw-bold mb-4">Comparación de Categorías</h1>

    <!-- Categorías sin ventas -->
    @if (isset($categoriesWithoutSales) && $categoriesWithoutSales->isNotEmpty())
        <div class="alert alert-warning">
            <strong><i class="fas fa-exclamation-circle me-2"></i>Categorías sin ventas:</strong>
            <ul class="mb-0">
                @foreach ($categoriesWithoutSales as $category)
                    <li>{{ $category->name }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario para seleccionar categorías -->
    @if (isset($categories) && $categories->isNotEmpty())
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Seleccionar Categorías para Comparar</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.categories.compare') }}" class="row g-3">
                    <div class="col-md-5">
                        <label for="category_a" class="form-label">Categoría A</label>
                        <select name="category_a" id="category_a" class="form-select">
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
                        <select name="category_b" id="category_b" class="form-select">
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
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            No hay categorías disponibles para realizar la comparación.
        </div>
    @endif

    <!-- Resultados de la comparación -->
    @if (isset($result))
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Resultados de la Comparación</h5>
            </div>
            <div class="card-body">
                <p class="fs-5">{{ $result }}</p>
                <ul class="mb-0">
                    <li><strong>{{ $categoryA->name }}:</strong> {{ $totalA }} ventas</li>
                    <li><strong>{{ $categoryB->name }}:</strong> {{ $totalB }} ventas</li>
                </ul>
            </div>
        </div>
    @endif
</div>
@endsection
