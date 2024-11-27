@extends('layouts.app')

@section('title', 'Predicción de Ventas')

@section('content')
    <h1>Predicción de Ventas</h1>

    <div class="card mb-4">
        <div class="card-header">
            Categoría Predicha
        </div>
        <div class="card-body">
            <h5 class="card-title">Categoría con mayor demanda la próxima semana</h5>
            <p class="card-text">
                <strong>{{ $bestCategoryName }}</strong>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Ventas por Categoría (Últimos 7 días)
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Ventas Totales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salesByCategory as $categoryId => $totalSales)
                        <tr>
                            <td>{{ $lastWeekSales->firstWhere('id', $categoryId)->name ?? 'Sin datos' }}</td>
                            <td>{{ $totalSales }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
