@extends('layouts.costum')

@section('title', 'Dashboard del Cliente')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Productos Disponibles</h1>

        <!-- Alertas -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filtro por categoría -->
        <div class="mb-4">
            <form method="GET" action="{{ route('customer.dashboard') }}" class="row g-3">
                <div class="col-md-4">
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas las categorías</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>

        <!-- Lista de productos -->
        <div class="row">
            @forelse ($products as $product)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                            <p class="card-text"><strong>Precio:</strong> ${{ number_format($product->price, 2) }}</p>
                            <p class="card-text"><strong>Stock Disponible:</strong> {{ $product->stock }}</p>
                            <form action="{{ route('customer.cart.add', $product->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="quantity">Cantidad:</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" max="{{ $product->stock }}">
                                </div>
                                <button type="submit" class="btn btn-success">Agregar al carrito</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">No hay productos disponibles en este momento.</p>
            @endforelse
        </div>

        <!-- Paginación -->
        @if ($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection
