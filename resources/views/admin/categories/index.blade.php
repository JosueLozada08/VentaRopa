@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
<h1>Lista de Categorías</h1>
<a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Crear Categoría</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No hay categorías.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
