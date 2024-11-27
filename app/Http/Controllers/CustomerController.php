<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Muestra el dashboard del cliente con productos disponibles.
     */
    public function dashboard()
    {
        $products = Product::where('stock', '>', 0)->get(); // Solo productos con stock disponible
        return view('costumer.dashboard', compact('products'));
    }

    /**
     * Muestra el carrito del cliente.
     */
    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
        return view('costumer.cart', compact('cart', 'total'));
    }

    /**
     * Agregar un producto al carrito.
     */
    public function addToCart(Product $product, Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('customer.dashboard')->with('success', 'Producto agregado al carrito.');
    }

    /**
     * Eliminar un producto del carrito.
     */
    public function removeFromCart(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('customer.cart')->with('success', 'Producto eliminado del carrito.');
    }

    /**
     * Confirmar la compra de todos los productos en el carrito.
     */
    public function confirmOrder()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.cart')->with('error', 'El carrito está vacío.');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $cart)),
            'status' => 'pendiente',
        ]);

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $order->products()->attach($product->id, ['quantity' => $item['quantity']]);
                $product->decrement('stock', $item['quantity']);
            }
        }

        session()->forget('cart');

        return redirect()->route('customer.dashboard')->with('success', 'Pedido confirmado con éxito.');
    }
}
