<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    protected $table = 'order_product'; // Asegúrate de que este sea el nombre correcto de tu tabla intermedia.

    protected $fillable = [
        'order_id',    // ID de la orden
        'product_id',  // ID del producto
        'quantity',    // Cantidad del producto en la orden
    ];
}
