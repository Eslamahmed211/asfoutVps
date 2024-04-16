<?php

use App\Http\Controllers\trader\traderController;
use App\Http\Controllers\users\payment\paymentController;
use App\Http\Controllers\users\wallet\walletController;
use Illuminate\Support\Facades\Route;


Route::get('home', [traderController::class, 'index']);

Route::get('product_percent', [traderController::class, 'product_percent']);
Route::get('variant_percent/{id}', [traderController::class, 'variant_percent']);
Route::get('products', [traderController::class, 'products']);
Route::get('products/{id}', [traderController::class, 'show']);
Route::get('orders', [traderController::class, 'orders']);
Route::get('orders/search', [traderController::class, 'search']);



Route::prefix("wallet")->group(function () {

    Route::get('/', [walletController::class, 'index']);

    Route::post('/', [walletController::class, 'store']);
});




Route::prefix("payment-methods")->group(function () {
    Route::get('create', [paymentController::class, 'create']);
    Route::post('/', [paymentController::class, 'store']);
    Route::get('{id}/edit', [paymentController::class, 'edit']);
    Route::put('{id}', [paymentController::class, 'update']);
    Route::delete('destroy', [paymentController::class, 'destroy']);
});



Route::prefix("invoices")->group(function () {
    Route::get('/', [traderController::class, 'invoices']);
    Route::get('search', [traderController::class, 'invoices_search']);
    Route::get('{invoice}/show', [traderController::class, 'invoice_show']);
  });
