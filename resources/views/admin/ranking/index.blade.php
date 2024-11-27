@extends('layouts.app')

@section('title', 'Ranking de Productos Más Vendidos')

@section('content')
<div class="container">
    <h1 class="mb-4">Ranking de Productos Más Vendidos</h1>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre del Producto</th>
                <th>Precio</th>
                <th>Cantidad Vendida</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($topProducts as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->total_quantity }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay datos disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
