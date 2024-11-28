@extends('layouts.app')

@section('title', 'Órdenes')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Órdenes</h1>
    <p class="text-muted">Revisa y administra las órdenes realizadas en la tienda.</p>

    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Órdenes Actuales</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            
                            <td>{{ $order->user->name ?? 'Cliente desconocido' }}</td>
                            <td>${{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge 
                                    @if($order->status === 'completado') bg-success
                                    @elseif($order->status === 'pendiente') bg-warning
                                    @else bg-danger
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : 'Fecha no disponible' }}</td>
                            <td>
                                @if (strtolower($order->status) === 'pendiente')
                                    <!-- Botón para completar la orden -->
                                    <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Estás seguro de completar esta orden?')">
                                            Completar
                                        </button>
                                    </form>

                                    <!-- Botón para eliminar la orden -->
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta orden?')">
                                            Eliminar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay órdenes registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection