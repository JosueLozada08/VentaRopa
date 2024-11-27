@extends('layouts.app')

@section('title', 'Dashboard de Administración')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard de Administración</h1>
    <p class="text-muted">Desde aquí puedes gestionar los módulos principales de la tienda.</p>

    <!-- Alerta de productos con bajo stock -->
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <div>
            @if ($lowStockProducts->count() > 0)
                <h5 class="alert-heading">¡Productos con bajo stock!</h5>
                <ul>
                    @foreach ($lowStockProducts as $product)
                        <li>
                            <strong>{{ $product->name }}</strong> - Stock: 
                            <span class="badge bg-danger">{{ $product->stock }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <h5 class="alert-heading">Aún no se han registrado nuevos productos.</h5>
            @endif
        </div>
    </div>

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

        <!-- Total de Ventas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-danger text-white shadow">
                <div class="card-body">
                    <h5>Total Ventas</h5>
                    <h2>${{ number_format($totalRevenue, 2) }}</h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.orders.index') }}" class="small text-white stretched-link">Ver detalles</a>
                    <div class="small text-white"><i class="fas fa-arrow-right"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
