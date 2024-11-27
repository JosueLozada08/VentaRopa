<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->get(); // Relación con usuario
        return view('admin.orders.index', compact('orders'));
    }

    public function complete(Order $order)
    {
        if ($order->status !== 'pendiente') {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Solo puedes completar órdenes pendientes.');
        }

        $order->update(['status' => 'completado']);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Orden completada correctamente.');
    }

    public function destroy(Order $order)
    {
        if ($order->status === 'completado') {
            return redirect()->route('admin.orders.index')
                ->with('error', 'No puedes eliminar una orden completada.');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Orden eliminada correctamente.');
    }
}