<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;

class PredictionController extends Controller
{
    public function predictBestCategory()
    {
        // Obtener las ventas de los últimos 7 días
        $lastWeekSales = Order::whereBetween('created_at', [
                Carbon::now()->subDays(7),
                Carbon::now()
            ])
            ->with('categories')
            ->get()
            ->flatMap(function ($order) {
                return $order->categories;
            });

        // Agrupar por categoría y calcular ventas totales
        $salesByCategory = $lastWeekSales->groupBy('id')->map(function ($sales) {
            return $sales->sum('total_sales');
        });

        // Predecir la categoría con más ventas
        $bestCategory = $salesByCategory->sortDesc()->keys()->first();

        // Información de la categoría
        $bestCategoryName = $lastWeekSales->firstWhere('id', $bestCategory)->name ?? 'Sin datos';

        return view('admin.predictions.index', compact('bestCategoryName', 'salesByCategory'));
    }
}
