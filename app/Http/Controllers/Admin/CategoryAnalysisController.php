<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $categories = Category::select('categories.name')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('orders.status', 'completado')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('orders.created_at', [$startDate, $endDate]);
            })
            ->selectRaw('SUM(order_product.quantity) as total_products_sold')
            ->selectRaw('SUM(order_product.quantity * products.price) as total_revenue') // Ajuste aquí
            ->groupBy('categories.id', 'categories.name')
            ->get();

        return view('admin.category-analysis.index', compact('categories'));
    }
}
