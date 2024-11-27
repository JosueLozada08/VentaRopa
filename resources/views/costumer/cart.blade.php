@extends('layouts.costum')

@section('title', 'Carrito de Compras')

@section('content')
<div class="container mt-4">
    <h1>Tu Carrito</h1>
    @if ($cart)
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $id => $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>${{ number_format($item['price'], 2) }}</td>
                        <td>${{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                        <td>
                            <form action="{{ route('customer.cart.remove', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-end">
            <h3>Total: ${{ number_format($total, 2) }}</h3>
            <form action="{{ route('customer.confirmOrder') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Confirmar Pedido</button>
            </form>
        </div>
    @else
        <p>No hay productos en tu carrito.</p>
    @endif
</div>
@endsection
