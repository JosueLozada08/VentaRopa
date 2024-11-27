<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Schema; // Importar Schema correctamente

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
        } else {
            // Valores predeterminados si 'status' no existe
            $totalRevenue = 0;
            $currentYearRevenue = collect();
            $lastYearRevenue = collect();
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
            'lowStockProducts'
        ));
    }
}
