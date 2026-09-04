<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', ContactController::class)->name('contact');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/categories/{category}/products', [HomeController::class, 'products'])->name('categories.products');
Route::get('/products/{product}', [HomeController::class, 'product'])->name('products.detail');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'login'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'authenticate'])->name('login.store');
    Route::get('/register', [CustomerAuthController::class, 'register'])->name('customer.register');
    Route::post('/register', [CustomerAuthController::class, 'store'])->name('customer.store');
    Route::get('/forgot-password', [CustomerAuthController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password', [CustomerAuthController::class, 'sendPasswordOtp'])->name('password.otp.send');
    Route::get('/forgot-password/otp', [CustomerAuthController::class, 'otpForm'])->name('password.otp');
    Route::post('/forgot-password/otp', [CustomerAuthController::class, 'verifyPasswordOtp'])->name('password.otp.verify');
    Route::get('/reset-password', [CustomerAuthController::class, 'resetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [CustomerAuthController::class, 'resetPassword'])->name('password.reset.save');
});
Route::post('/logout', [CustomerAuthController::class, 'logout'])->middleware('auth')->name('customer.logout');
Route::get('/account', [CustomerAuthController::class, 'account'])->middleware('auth')->name('customer.account');
Route::patch('/account', [CustomerAuthController::class, 'updateAccount'])->middleware('auth')->name('customer.account.update');
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/address', [CartController::class, 'saveAddress'])->name('checkout.address');
    Route::get('/payment', [CartController::class, 'payment'])->name('payment');
    Route::post('/razorpay/order', [RazorpayController::class, 'create'])->name('razorpay.order');
    Route::post('/razorpay/verify', [RazorpayController::class, 'verify'])->name('razorpay.verify');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');
});

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'store'])->name('admin.login.store');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::resource('customers', CustomerController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
});
