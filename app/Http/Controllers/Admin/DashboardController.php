<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Consultas básicas
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();

        // Validar si existe el campo 'status' antes de usarlo
        if (Schema::hasColumn('orders', 'status')) {
            $totalRevenue = Order::where('status', 'completado')->sum('total');

            // Ingresos mensuales del año actual
            $currentYearRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
                ->where('status', 'completado')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [date('F', mktime(0, 0, 0, $item->month, 1)) => $item->total];
                });

            // Ingresos mensuales del año pasado
            $lastYearRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
                ->where('status', 'completado')
                ->whereYear('created_at', date('Y') - 1)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [date('F', mktime(0, 0, 0, $item->month, 1)) => $item->total];
                });

            // Calcular la categoría más vendida
            $predictedCategory = Category::select('categories.*')
                ->join('products', 'categories.id', '=', 'products.category_id')
                ->join('order_product', 'products.id', '=', 'order_product.product_id')
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->where('orders.status', 'completado')
                ->whereBetween('orders.created_at', [now()->subDays(30), now()]) // Últimos 30 días
                ->groupBy('categories.id')
                ->orderByRaw('SUM(order_product.quantity) DESC')
                ->first();
        } else {
            // Valores predeterminados si 'status' no existe
            $totalRevenue = 0;
            $currentYearRevenue = collect();
            $lastYearRevenue = collect();
            $predictedCategory = null;
        }

        // Productos con bajo stock
        $lowStockProducts = Product::where('stock', '<', 5)->get();

        // Retornar la vista con los datos necesarios
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'totalRevenue',
            'currentYearRevenue',
            'lastYearRevenue',
            'lowStockProducts',
            'predictedCategory'
        ));
    }
}
