<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\Payment\PaymentController;
use App\Http\Controllers\Backend\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//auth route
Auth::routes();


// payment route
Route::middleware(['auth'])->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payment.form');
    Route::post('/process', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/success/{transaction_id}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed/{transaction_id}', [PaymentController::class, 'failure'])->name('payment.failure');
});


