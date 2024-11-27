<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductRankingController extends Controller
{
    public function index()
    {
        // Obtener los productos más vendidos
        $topProducts = Product::select('products.id', 'products.name', 'products.price')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('orders.status', 'completado') // Filtrar órdenes completadas
            ->groupBy('products.id', 'products.name', 'products.price')
            ->selectRaw('SUM(order_product.quantity) as total_quantity')
            ->orderByDesc('total_quantity') // Ordenar por cantidad vendida
            ->take(10) // Top 10 productos más vendidos
            ->get();

        return view('admin.product-ranking.index', compact('topProducts'));
    }
}
