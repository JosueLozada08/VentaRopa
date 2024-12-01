<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard con estadísticas, predicción y ranking de productos.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Consultas básicas de estadísticas
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();

        // Obtener el número de días del request 
        $days = (int) $request->get('days', 30);

        // Validar si existe el campo 'status' en la tabla orders
        if (Schema::hasColumn('orders', 'status')) {
            // Ingresos totales de órdenes completadas
            $totalRevenue = Order::where('status', 'completado')->sum('total');

            // Predicción de la categoría más vendida
            $predictedCategory = $this->getPredictedCategory($days);

            // Ranking de los productos más vendidos
            $topSellingProducts = $this->getTopSellingProducts($days);
        } else {
            $totalRevenue = 0;
            $predictedCategory = null;
            $topSellingProducts = collect();
        }

        // Obtener productos con bajo stock
        $lowStockProducts = Product::where('stock', '<', 5)->get();

        // Retornar la vista con las variables necesarias
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'totalRevenue',
            'lowStockProducts',
            'predictedCategory',
            'topSellingProducts',
            'days'
        ));
    }

    /**
     * Obtiene la categoría más vendida en un rango de días.
     *
     * @param int $days
     * @return object|null
     */
    private function getPredictedCategory(int $days)
    {
        // Consultar la categoría con más ventas en el rango de días
        $predictedCategory = Category::select('categories.id', 'categories.name')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('orders.status', 'completado')
            ->whereBetween('orders.created_at', [now()->subDays($days), now()])
            ->selectRaw('SUM(order_product.quantity) as total_sold')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->first();

        // Calcular la probabilidad de la categoría más vendida
        if ($predictedCategory) {
            $totalSoldInPeriod = Order::join('order_product', 'orders.id', '=', 'order_product.order_id')
                ->where('orders.status', 'completado')
                ->whereBetween('orders.created_at', [now()->subDays($days), now()])
                ->sum('order_product.quantity');

            $predictedCategory->probability = $totalSoldInPeriod > 0
                ? round(($predictedCategory->total_sold / $totalSoldInPeriod) * 100, 2)
                : 0;
        }

        return $predictedCategory;
    }

    /**
     * Obtiene los productos más vendidos en un rango de días.
     *
     * @param int $days
     * @return \Illuminate\Support\Collection
     */
    private function getTopSellingProducts(int $days)
    {
        return Product::select(
                'products.name',
                'categories.name as category',
                DB::raw('SUM(order_product.quantity) as total_sold')
            )
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', 'completado')
            ->whereBetween('orders.created_at', [now()->subDays($days), now()])
            ->groupBy('products.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
    }
}
