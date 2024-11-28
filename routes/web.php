<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PredictionController;
use App\Http\Controllers\Admin\ProductRankingController;
use App\Http\Controllers\Admin\CategoryComparisonController;
use App\Http\Controllers\CustomerController;

// Ruta principal de bienvenida
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas de autenticación para usuarios invitados
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', function () {
        return auth()->user()->is_admin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('customer.dashboard');
    })->name('dashboard');
    Route::get('/email/verify', [VerifyEmailController::class, 'prompt'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware(['signed'])
        ->name('verification.verify');
    Route::post('/email/resend', [VerifyEmailController::class, 'sendVerificationEmail'])->name('verification.send');
});

// Rutas para administradores
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('orders', OrderController::class);

    // Ruta para marcar órdenes como completas
    Route::put('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');

    // Ruta para predicción dinámica
    Route::get('/predictions/{days?}', [PredictionController::class, 'predictBestCategory'])
        ->where('days', '[0-9]+')
        ->name('predictions');

    // Ranking de productos más vendidos
    Route::get('/ranking', [ProductRankingController::class, 'index'])->name('ranking');

    // Comparar categorías
    Route::get('/comparison', [CategoryComparisonController::class, 'index'])->name('categories.comparison');
    Route::get('/comparison/compare', [CategoryComparisonController::class, 'compare'])->name('categories.compare');
});

// Rutas para clientes
Route::prefix('customer')->name('customer.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
    Route::post('/cart/{product}/add', [CustomerController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/{product}/remove', [CustomerController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/confirm-order', [CustomerController::class, 'confirmOrder'])->name('confirmOrder');
    Route::post('/products/{product}/buy', [CustomerController::class, 'buy'])->name('products.buy');
});
