@extends('layouts.app')

@section('title', isset($product) ? 'Editar Producto' : 'Crear Producto')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">{{ isset($product) ? 'Editar Producto' : 'Crear Producto' }}</h1>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ isset($product) ? 'Editar Producto' : 'Nuevo Producto' }}</h5>
        </div>
        <div class="card-body">
            <form 
                action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" 
                method="POST"
            >
                @csrf
                @if (isset($product))
                    @method('PUT')
                @endif

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $product->name ?? '') }}" 
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea 
                        class="form-control @error('description') is-invalid @enderror" 
                        id="description" 
                        name="description" 
                        rows="4"
                        required
                    >{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Precio -->
                <div class="mb-3">
                    <label for="price" class="form-label">Precio</label>
                    <input 
                        type="number" 
                        class="form-control @error('price') is-invalid @enderror" 
                        id="price" 
                        name="price" 
                        value="{{ old('price', $product->price ?? '') }}" 
                        step="0.01" 
                        required
                    >
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Stock -->
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input 
                        type="number" 
                        class="form-control @error('stock') is-invalid @enderror" 
                        id="stock" 
                        name="stock" 
                        value="{{ old('stock', $product->stock ?? 0) }}" 
                        min="0"
                    >
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Categoría -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">Categoría</label>
                    <select 
                        class="form-control @error('category_id') is-invalid @enderror" 
                        id="category_id" 
                        name="category_id" 
                        required
                    >
                        <option value="">Selecciona una categoría</option>
                        @foreach ($categories as $category)
                            <option 
                                value="{{ $category->id }}" 
                                {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Actualizar' : 'Crear' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
