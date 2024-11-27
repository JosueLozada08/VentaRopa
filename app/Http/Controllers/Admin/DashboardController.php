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

        // Validar si existe el campo 'status'
        if (Schema::hasColumn('orders', 'status')) {
            $totalRevenue = Order::where('status', 'completado')->sum('total');

            // Predicción de la categoría más vendida
            $predictedCategory = Category::select('categories.id', 'categories.name')
                ->join('products', 'categories.id', '=', 'products.category_id')
                ->join('order_product', 'products.id', '=', 'order_product.product_id')
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->where('orders.status', 'completado')
                ->whereBetween('orders.created_at', [now()->subDays(30), now()])
                ->selectRaw('SUM(order_product.quantity) as total_sold')
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('total_sold')
                ->first();

            if ($predictedCategory) {
                $totalSoldInPeriod = Order::join('order_product', 'orders.id', '=', 'order_product.order_id')
                    ->where('orders.status', 'completado')
                    ->whereBetween('orders.created_at', [now()->subDays(30), now()])
                    ->sum('order_product.quantity');

                $predictedCategory->probability = round(($predictedCategory->total_sold / $totalSoldInPeriod) * 100, 2);
            }

            // Ranking de productos más vendidos
            $topSellingProducts = Product::select(
                'products.name', 
                'categories.name as category', 
                \Illuminate\Support\Facades\DB::raw('SUM(order_product.quantity) as total_sold')
            )
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', 'completado')
            ->groupBy('products.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
        
        } else {
            $totalRevenue = 0;
            $predictedCategory = null;
            $topSellingProducts = collect();
        }

        // Productos con bajo stock
        $lowStockProducts = Product::where('stock', '<', 5)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'totalRevenue',
            'lowStockProducts',
            'predictedCategory',
            'topSellingProducts'
        ));
    }
}
