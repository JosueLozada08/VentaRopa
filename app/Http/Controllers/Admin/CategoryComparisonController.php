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
                ->havingRaw('total_sold = 0')
                ->get();

            return view('admin.comparison.index', compact('categories', 'categoriesWithoutSales'));
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
        if (!$categoryA || !isset($categoryA->total_sold) || $categoryA->total_sold == 0) {
            $categoryAName = $categoryA->name ?? 'desconocida';
            return redirect()->route('admin.categories.comparison')
                ->withErrors("La categoría '{$categoryAName}' no tiene datos de ventas y no se puede comparar.");
        }

        // Verificar si la categoría B existe y tiene datos de ventas
        if (!$categoryB || !isset($categoryB->total_sold) || $categoryB->total_sold == 0) {
            $categoryBName = $categoryB->name ?? 'desconocida';
            return redirect()->route('admin.categories.comparison')
                ->withErrors("La categoría '{$categoryBName}' no tiene datos de ventas y no se puede comparar.");
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

        /**
         * Obtiene los datos de ventas de una categoría.
         */
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
    }
