@extends('layouts.app')

@section('title', 'Dashboard de Administración')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Dashboard de Administración</h1>
        <p class="text-muted">Administra tu tienda de manera eficiente.</p>
    </div>

    <!-- Alertas de productos con bajo stock -->
    @if ($lowStockProducts->count() > 0)
        <div class="alert alert-warning shadow-sm">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Productos con bajo stock:</h5>
            <ul class="mb-0">
                @foreach ($lowStockProducts as $product)
                    <li>{{ $product->name }} - Stock: <strong>{{ $product->stock }}</strong></li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="alert alert-info shadow-sm">
            <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>No hay productos con bajo stock.</h5>
        </div>
    @endif

    <!-- Tarjetas de estadísticas -->
    <div class="row g-4">
        <!-- Total de Productos -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <h6>Total Productos</h6>
                    <h2 class="fw-bold">{{ $totalProducts }}</h2>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.products.index') }}" class="text-white text-decoration-none">Ver productos</a>
                    <i class="fas fa-box fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Total de Categorías -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <h6>Total Categorías</h6>
                    <h2 class="fw-bold">{{ $totalCategories }}</h2>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.categories.index') }}" class="text-white text-decoration-none">Ver categorías</a>
                    <i class="fas fa-tags fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Total de Órdenes -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 bg-warning text-white">
                <div class="card-body">
                    <h6>Total Órdenes</h6>
                    <h2 class="fw-bold">{{ $totalOrders }}</h2>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.orders.index') }}" class="text-white text-decoration-none">Ver órdenes</a>
                    <i class="fas fa-shopping-cart fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Total de Ingresos -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 bg-danger text-white">
                <div class="card-body">
                    <h6>Total Ingresos</h6>
                    <h2 class="fw-bold">${{ number_format($totalRevenue, 2) }}</h2>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.orders.index') }}" class="text-white text-decoration-none">Ver ingresos</a>
                    <i class="fas fa-dollar-sign fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Predicción de categoría más vendida -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Predicción: Categoría más vendida</h5>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex">
                        <input 
                            type="number" 
                            name="days" 
                            class="form-control me-2" 
                            placeholder="Días" 
                            value="{{ $days }}" 
                            min="1" 
                            max="365"
                        >
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
                <div class="card-body">
                    @if ($predictedCategory)
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <h6 class="text-success">
                                    Categoría: <strong>{{ $predictedCategory->name }}</strong>
                                </h6>
                                <p>Probabilidad: <strong>{{ $predictedCategory->probability ?? 0 }}%</strong></p>
                            </div>
                            <div class="progress w-50">
                                <div 
                                    class="progress-bar progress-bar-striped bg-success" 
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
                        <p class="text-muted">No hay datos suficientes para realizar una predicción.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ranking de productos más vendidos -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Ranking: Productos más vendidos</h5>
                </div>
                <div class="card-body">
                    @if ($topSellingProducts->isNotEmpty())
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Posición</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Ventas</th>
                                </tr>
                            </thead>
                            <tbody>a@extends('layouts.app')

@section('title', 'Dashboard de Administración')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Dashboard de Administración</h1>
        <p class="text-muted">Administra tu tienda de manera eficiente.</p>
    </div>

    <!-- Alertas de productos con bajo stock -->
    @if ($lowStockProducts->count() > 0)
        <div class="alert alert-warning shadow-sm">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Productos con bajo stock:</h5>
            <ul class="mb-0">
                @foreach ($lowStockProducts as $product)
                    <li>{{ $product->name }} - Stock: <strong>{{ $product->stock }}</strong></li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="alert alert-info shadow-sm">
            <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>No hay productos con bajo stock.</h5>
        </div>
    @endif

    <!-- Tarjetas de estadísticas -->
    <div class="row g-4">
        <!-- Total de Productos -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <h6>Total Productos</h6>
                    <h2 class="fw-bold">{{ $totalProducts }}</h2>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.products.index') }}" class="text-white text-decoration-none">Ver productos</a>
                    <i class="fas fa-box fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Total de Categorías -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <h6>Total Categorías</h6>
                    <h2 class="fw-bold">{{ $totalCategories }}</h2>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.categories.index') }}" class="text-white text-decoration-none">Ver categorías</a>
                    <i class="fas fa-tags fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Total de Órdenes -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 bg-warning text-white">
                <div class="card-body">
                    <h6>Total Órdenes</h6>
                    <h2 class="fw-bold">{{ $totalOrders }}</h2>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.orders.index') }}" class="text-white text-decoration-none">Ver órdenes</a>
                    <i class="fas fa-shopping-cart fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Total de Ingresos -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 bg-danger text-white">
                <div class="card-body">
                    <h6>Total Ingresos</h6>
                    <h2 class="fw-bold">${{ number_format($totalRevenue, 2) }}</h2>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.orders.index') }}" class="text-white text-decoration-none">Ver ingresos</a>
                    <i class="fas fa-dollar-sign fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Predicción de categoría más vendida -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Predicción: Categoría más vendida</h5>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex">
                        <input 
                            type="number" 
                            name="days" 
                            class="form-control me-2" 
                            placeholder="Días" 
                            value="{{ $days }}" 
                            min="1" 
                            max="365"
                        >
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
                <div class="card-body">
                    @if ($predictedCategory)
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <h6 class="text-success">
                                    Categoría: <strong>{{ $predictedCategory->name }}</strong>
                                </h6>
                                <p>Probabilidad: <strong>{{ $predictedCategory->probability ?? 0 }}%</strong></p>
                            </div>
                            <div class="progress w-50">
                                <div 
                                    class="progress-bar progress-bar-striped bg-success" 
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
                        <p class="text-muted">No hay datos suficientes para realizar una predicción.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ranking de productos más vendidos -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Ranking: Productos más vendidos</h5>
                </div>
                <div class="card-body">
                    @if ($topSellingProducts->isNotEmpty())
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Posición</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Ventas</th>
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
                        <p class="text-muted">No hay datos suficientes para mostrar el ranking.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
                        <p class="text-muted">No hay datos suficientes para mostrar el ranking.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
