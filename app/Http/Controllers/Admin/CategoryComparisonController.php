<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryComparisonController extends Controller
{
    public function index()
    {
        // Obtener todas las categorías para el formulario
        $categories = Category::all();

        return view('admin.comparison.index', compact('categories'));
    }

    public function compare(Request $request)
    {
        // Validar las categorías seleccionadas
        $request->validate([
            'category_a' => 'required|exists:categories,id|different:category_b',
            'category_b' => 'required|exists:categories,id',
        ]);

        $categoryAId = $request->get('category_a');
        $categoryBId = $request->get('category_b');

        // Obtener datos de ventas de las categorías
        $categoryA = Category::select('categories.id', 'categories.name')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('orders.status', 'completado')
            ->where('categories.id', $categoryAId)
            ->selectRaw('SUM(order_product.quantity) as total_sold')
            ->groupBy('categories.id', 'categories.name')
            ->first();

        $categoryB = Category::select('categories.id', 'categories.name')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('orders.status', 'completado')
            ->where('categories.id', $categoryBId)
            ->selectRaw('SUM(order_product.quantity) as total_sold')
            ->groupBy('categories.id', 'categories.name')
            ->first();

            // Verificar si alguna categoría está vacía o no tiene datos de ventas
            if (!$categoryA || !isset($categoryA->total_sold) || $categoryA->total_sold == 0) {
                $categoryAName = $categoryA->name ?? 'desconocida';
                return redirect()->route('admin.categories.comparison')->withErrors("La categoría '{$categoryAName}' no tiene datos de ventas y no se puede comparar.");
            }

            if (!$categoryB || !isset($categoryB->total_sold) || $categoryB->total_sold == 0) {
                $categoryBName = $categoryB->name ?? 'desconocida';
                return redirect()->route('admin.categories.comparison')->withErrors("La categoría '{$categoryBName}' no tiene datos de ventas y no se puede comparar.");
            }

        // Calcular las ventas totales
        $totalA = $categoryA->total_sold ?? 0;
        $totalB = $categoryB->total_sold ?? 0;

        // Calcular la diferencia en porcentaje
        $difference = $totalA - $totalB;
        $percentageDifference = $totalB > 0
            ? round(($difference / $totalB) * 100, 2)
            : ($totalA > 0 ? 100 : 0);

        // Determinar cuál categoría tiene mejores ventas
        $result = $difference > 0
            ? "{$categoryA->name} ha vendido un {$percentageDifference}% más que {$categoryB->name}."
            : ($difference < 0
                ? "{$categoryB->name} ha vendido un " . abs($percentageDifference) . "% más que {$categoryA->name}."
                : "Ambas categorías tienen las mismas ventas.");

        return view('admin.comparison.index', compact('categoryA', 'categoryB', 'totalA', 'totalB', 'result'))
            ->with('categories', Category::all());
    }
}
