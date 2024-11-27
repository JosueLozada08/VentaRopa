@extends('layouts.app')

@section('title', 'Dashboard de Administración')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard de Administración</h1>
    <p class="text-muted">Desde aquí puedes gestionar los módulos principales de la tienda.</p>

    <!-- Alertas de productos con bajo stock -->
    @if ($lowStockProducts->count() > 0)
        <div class="alert alert-warning">
            <h5 class="alert-heading">Productos con bajo stock:</h5>
            <ul>
                @foreach ($lowStockProducts as $product)
                    <li>{{ $product->name }} - Stock: {{ $product->stock }}</li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="alert alert-info">
            <h5 class="alert-heading">No hay productos con bajo stock.</h5>
        </div>
    @endif

    <!-- Tarjetas de estadísticas -->
    <div class="row">
        <!-- Total de Productos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5>Total Productos</h5>
                    <h2>{{ $totalProducts }}</h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.products.index') }}" class="small text-white stretched-link">Ver productos</a>
                    <div class="small text-white"><i class="fas fa-arrow-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Total de Categorías -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5>Total Categorías</h5>
                    <h2>{{ $totalCategories }}</h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.categories.index') }}" class="small text-white stretched-link">Ver categorías</a>
                    <div class="small text-white"><i class="fas fa-arrow-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Total de Órdenes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <h5>Total Órdenes</h5>
                    <h2>{{ $totalOrders }}</h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.orders.index') }}" class="small text-white stretched-link">Ver órdenes</a>
                    <div class="small text-white"><i class="fas fa-arrow-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Total de Ingresos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-danger text-white shadow">
                <div class="card-body">
                    <h5>Total Ingresos</h5>
                    <h2>${{ number_format($totalRevenue, 2) }}</h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.orders.index') }}" class="small text-white stretched-link">Ver ingresos</a>
                    <div class="small text-white"><i class="fas fa-arrow-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Predicción de categoría más vendida -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Predicción: Categoría más vendida la próxima semana</h5>
                </div>
                <div class="card-body">
                @if ($predictedCategory)
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <h6 class="text-success">
                                    Se espera que la categoría más vendida sea: <strong>{{ $predictedCategory->name }}</strong>
                                </h6>
                                <p>Probabilidad de éxito: <strong>{{ $predictedCategory->probability ?? 0 }}%</strong></p>
                            </div>
                            <div class="progress w-50">
                                <div 
                                    class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                    role="progressbar" 
                                    style="width: {{ $predictedCategory->probability ?? 0 }}%;" 
                                    aria-valuenow="{{ $predictedCategory->probability ?? 0 }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100"
                                >
                                    {{ $predictedCategory->probability ?? 0 }}%
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Aún no hay suficientes datos para realizar una predicción.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Ranking de productos más vendidos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Ranking: Productos más vendidos</h5>
                </div>
                <div class="card-body">
                    @if ($topSellingProducts->isNotEmpty())
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Posición</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Cantidad Vendida</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topSellingProducts as $index => $product)
                                    <tr>
                                        <td>#{{ $index + 1 }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category }}</td>
                                        <td>{{ $product->total_sold }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No hay datos de ventas suficientes para mostrar el ranking.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
