<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total',
        'status',
    ];

    /**
     * Relación con el usuario que realizó la orden.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los productos de la orden.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity') // Incluye la cantidad comprada en la relación
                    ->withTimestamps();
    }

    /**
     * Relación con categorías a través de los productos.
     * Esto nos permitirá agrupar las ventas por categoría.
     */
    public function categories()
    {
        return $this->products()
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->selectRaw('categories.id, categories.name, SUM(order_product.quantity) as total_sales')
                    ->groupBy('categories.id', 'categories.name');
    }
}
