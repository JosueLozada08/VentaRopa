@extends('layouts.costum')

@section('title', 'Productos Disponibles')

@section('content')
<div class="container mt-4">
    <h1>Productos Disponibles</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="card-text"><strong>${{ number_format($product->price, 2) }}</strong></p>
                        <p class="card-text"><strong>Stock: </strong>{{ $product->stock }}</p>
                        <form action="{{ route('customer.products.buy', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="quantity">Cantidad:</label>
                                <input type="number" name="quantity" class="form-control" min="1" max="{{ $product->stock }}" value="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="client_name">Nombre del Cliente:</label>
                                <input type="text" name="client_name" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Comprar</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <p class="text-center">No hay productos disponibles.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
