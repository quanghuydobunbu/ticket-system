<?php

use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\VNPayController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('dashboard');

// Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/events', [HomeController::class, 'search_event'])->name('event');

Route::get('/cart', [HomeController::class, 'cart'])->name('cart')->middleware('auth');

Route::get('/checkout', function () {
    return view('page.checkout');
})->name('checkout')->middleware('auth');

Route::post('/vnpay/payment', [VNPayController::class, 'createPayment'])->name('vnpay.payment')->middleware('auth');
Route::get('/vnpay/callback', [VNPayController::class, 'callback'])->name('vnpay.callback')->middleware('auth');
Route::get('/payment/success', [VNPayController::class, 'success'])->name('payment.success')->middleware('auth');
Route::get('/payment/failed', [VNPayController::class, 'failed'])->name('payment.failed')->middleware('auth');

Route::get('/my-ticket', [HomeController::class, 'my_ticket'])->name('my-ticket')->middleware('auth');
Route::get('/my-tickets/{id}', [HomeController::class, 'show'])->name('my-tickets.show')->middleware('auth');
 Route::get('/my-tickets/{id}/download', [HomeController::class, 'download'])->name('my-tickets.download')->middleware('auth');