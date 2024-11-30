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

// Welcome route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
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

// Authenticated user routes
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

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('orders', OrderController::class);

    // Orders completion
    Route::put('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');

    // Predictions
    Route::get('/predictions/{days?}', [PredictionController::class, 'predictBestCategory'])
        ->where('days', '[0-9]+')
        ->name('predictions');

    // Ranking
    Route::get('/ranking', [ProductRankingController::class, 'index'])->name('ranking');

    // Category comparison
    Route::get('/comparison', [CategoryComparisonController::class, 'index'])->name('categories.comparison');
    Route::get('/comparison/compare', [CategoryComparisonController::class, 'compare'])->name('categories.compare');
});

// Customer routes
Route::prefix('customer')->name('customer.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard'); // Dashboard con filtro de categorías
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart'); // Ver carrito
    Route::post('/cart/{product}/add', [CustomerController::class, 'addToCart'])->name('cart.add'); // Agregar producto al carrito
    Route::delete('/cart/{product}/remove', [CustomerController::class, 'removeFromCart'])->name('cart.remove'); // Eliminar producto del carrito
    Route::post('/confirm-order', [CustomerController::class, 'confirmOrder'])->name('confirmOrder'); // Confirmar pedido
});
