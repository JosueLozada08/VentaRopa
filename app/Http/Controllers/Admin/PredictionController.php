<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function predictBestCategory(Request $request)
    {
        // Obtener el número de días desde el parámetro o usar un valor predeterminado (7 días)
        $days = $request->get('days', 7);

        // Validar que haya órdenes completadas en el rango de tiempo
        $orders = Order::where('status', 'completado')
            ->whereBetween('created_at', [now()->subDays($days), now()])
            ->get();

        if ($orders->isEmpty()) {
            // Si no hay órdenes en este rango de tiempo
            return view('admin.predictions.index', [
                'bestCategoryName' => 'Sin datos',
                'salesByCategory' => [],
                'days' => $days,
            ]);
        }

        // Obtener productos vendidos agrupados por categoría
        $salesByCategory = Category::select('categories.id', 'categories.name')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('orders.status', 'completado')
            ->whereBetween('orders.created_at', [now()->subDays($days), now()])
            ->selectRaw('SUM(order_product.quantity) as total_sales')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sales')
            ->get();

        if ($salesByCategory->isEmpty()) {
            return view('admin.predictions.index', [
                'bestCategoryName' => 'Sin datos',
                'salesByCategory' => [],
                'days' => $days,
            ]);
        }

        // Categoría más vendida
        $bestCategory = $salesByCategory->first();
        $bestCategoryName = $bestCategory->name;

        return view('admin.predictions.index', compact('bestCategoryName', 'salesByCategory', 'days'));
    }
}
