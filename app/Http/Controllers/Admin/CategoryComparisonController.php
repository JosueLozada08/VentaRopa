<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryComparisonController extends Controller
{
    /**
     * Muestra la vista de comparación de categorías.
     */
    public function index()
    {
        // Obtener todas las categorías
        $categories = Category::all();

        // Categorías sin ventas
        $categoriesWithoutSales = Category::select('categories.id', 'categories.name')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('order_product', 'products.id', '=', 'order_product.product_id')
            ->leftJoin('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('orders.status', 'completado')
            ->selectRaw('categories.id, categories.name, COALESCE(SUM(order_product.quantity), 0) as total_sold')
            ->groupBy('categories.id', 'categories.name')
            ->havingRaw('total_sold = 0')//identifica las categorias sin venta 
            ->get();

        return view('admin.comparison.index', compact('categories', 'categoriesWithoutSales'));
    }

     /**
     * Obtiene los datos de ventas de una categoría.
     */

     //el metodo encapsula la logica para obtener los datos de venta 
     private function getCategorySalesData($categoryId)
     {
         return Category::select('categories.id', 'categories.name')
             ->join('products', 'categories.id', '=', 'products.category_id')
             ->join('order_product', 'products.id', '=', 'order_product.product_id')
             ->join('orders', 'order_product.order_id', '=', 'orders.id')
             ->where('orders.status', 'completado')
             ->where('categories.id', $categoryId)
             ->selectRaw('SUM(order_product.quantity) as total_sold')
             ->groupBy('categories.id', 'categories.name')
             ->first();
     }

    /**
     * Compara dos categorías y muestra los resultados.
     */
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
        $categoryA = $this->getCategorySalesData($categoryAId);
        $categoryB = $this->getCategorySalesData($categoryBId);

        // Verificar si la categoría A existe y tiene datos de ventas
        if (!$categoryA || !isset($categoryA->total_sold)) {
            $categoryAName = Category::find($categoryAId)->name ?? 'desconocida';
            return redirect()->route('admin.categories.comparison')
                ->withErrors("Faltan datos en la categoría '{$categoryAName}', aún no se registran ventas.");
        }

        // Verificar si la categoría B existe y tiene datos de ventas
        if (!$categoryB || !isset($categoryB->total_sold)) {
            $categoryBName = Category::find($categoryBId)->name ?? 'desconocida';
            return redirect()->route('admin.categories.comparison')
                ->withErrors("Faltan datos en la categoría '{$categoryBName}', aún no se registran ventas.");
        }

        // Calcular las ventas totales
        $totalA = $categoryA->total_sold;
        $totalB = $categoryB->total_sold;

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
