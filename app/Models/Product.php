<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'products';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'stock', // Campo para gestionar el inventario
    ];

    /**
     * Relación con la categoría.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Verificar si el producto está agotado.
     */
    public function isOutOfStock()
    {
        return $this->stock <= 0;
    }
    
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}