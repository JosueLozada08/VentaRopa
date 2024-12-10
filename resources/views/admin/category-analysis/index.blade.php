@extends('layouts.app')

@section('title', 'Comparación de Categorías por Fechas')

@section('content')
<div class="container">
    <h1 class="my-4">Comparación de Categorías</h1>

    <!-- Formulario de fechas -->
    <form method="GET" action="{{ route('admin.categories.analysis') }}">
        <div class="row">
            <div class="col-md-5">
                <label for="start_date" class="form-label">Fecha Inicio:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}" required>
            </div>
            <div class="col-md-5">
                <label for="end_date" class="form-label">Fecha Fin:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de resultados -->
    @if(isset($categories) && $categories->isNotEmpty())
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre de Categoría</th>
                <th>Total Productos Vendidos</th>
                <th>Ingresos Totales</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $index => $category)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->total_products_sold }}</td>
                    <td>${{ number_format($category->total_revenue, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="alert alert-warning mt-4">
        No se encontraron resultados para el rango de fechas seleccionado.
    </div>
    @endif
</div>
@endsection
