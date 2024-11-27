<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PredictionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\CustomerController;

// Ruta principal de bienvenida
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas de autenticación para usuarios invitados
Route::middleware('guest')->group(function () {
    // Formulario de inicio de sesión
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    // Registro de usuarios
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Restablecimiento de contraseña
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {
    // Cerrar sesión
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Redirección al dashboard basado en roles
    Route::get('/dashboard', function () {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    // Verificación de correo electrónico
    Route::get('/email/verify', [VerifyEmailController::class, 'prompt'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware(['signed'])
        ->name('verification.verify');
    Route::post('/email/resend', [VerifyEmailController::class, 'sendVerificationEmail'])->name('verification.send');
});

// Rutas para administradores autenticados
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard de administración
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Gestión de productos
    Route::resource('products', ProductController::class);

    // Gestión de categorías
    Route::resource('categories', CategoryController::class);

    // Gestión de órdenes
    Route::resource('orders', OrderController::class);

    // Ruta para completar órdenes
    Route::put('orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::get('/admin/predictions', [PredictionController::class, 'predictBestCategory'])->name('admin.predictions');

});

// Rutas para clientes autenticados
Route::prefix('customer')->name('customer.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard del cliente
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    
    // Carrito de compras
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
    Route::post('/cart/{product}/add', [CustomerController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/{product}/remove', [CustomerController::class, 'removeFromCart'])->name('cart.remove');

    // Confirmar pedido
    Route::post('/confirm-order', [CustomerController::class, 'confirmOrder'])->name('confirmOrder');

    // Comprar producto directamente
    Route::post('/products/{product}/buy', [CustomerController::class, 'buy'])->name('products.buy');
});
