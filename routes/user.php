<?php

use App\Http\Controllers\User\HomeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('page.home');
// });

Route::get('/', [HomeController::class, 'index']);

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/events', [HomeController::class, 'event'])->name('event');

Route::get('/cart', function () {
    return view('page.cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('page.checkout');
})->name('checkout');

Route::get('/comfirm_checkout', function () {
    return view('page.comfirm_checkout');
})->name('comfirm_checkout');

