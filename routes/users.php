<?php

use App\Http\Controllers\users\cart\cartController;
use App\Http\Controllers\users\marketerController;
use App\Http\Controllers\users\moderators\moderatorsController;
use App\Http\Controllers\users\orders\detailsController;
use App\Http\Controllers\users\orders\orderController;
use App\Http\Controllers\users\payment\paymentController;
use App\Http\Controllers\users\wallet\walletController;
use App\Models\deliveryPrice;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::get('home', [marketerController::class, 'home']);

Route::get('products', [marketerController::class, 'productsPage']);
Route::get('products/search',  [marketerController::class, 'productsSearch']);
Route::get('products/{slug}',  [marketerController::class, 'showProductPage']);
Route::get('getVariant/{id}', [marketerController::class, 'getVariant']);


Route::get('add_fav/{id}', [marketerController::class, 'add_fav']);
Route::get('delete_fav/{id}', [marketerController::class, 'delete_fav']);


Route::prefix("cart")->group(function () {

    Route::post('/',  [cartController::class, 'store']);
    Route::get('/',  [cartController::class, 'index']);
    Route::delete('destroy',  [cartController::class, 'destroy']);
    Route::get('destroyAll',  [cartController::class, 'destroyAll']);

    Route::post('checkout',  [cartController::class, 'checkout']);


    Route::get('count', function () {
        return json(["cartCount" => cartCount()]);
    });

    Route::get('checkout', function () {
        $cities = deliveryPrice::orderBy("order", "Asc")->get();
        return view("users/cart/checkout", compact("cities"));
    });
});


Route::prefix("orders")->group(function () {

    Route::get('/',  [orderController::class, 'index']);
    Route::get('search',  [orderController::class, 'search']);
    Route::get('GetOrderDetailsAjax/{id}',  [orderController::class, 'GetOrderDetailsAjax']);
    Route::get('{order}/show',  [orderController::class, 'show']);
    Route::delete('details/destroy',  [orderController::class, 'destroyDetails']);
    Route::get('details/{id}/edit',  [detailsController::class, 'edit']);
    Route::put('details/{id}',  [detailsController::class, 'update']);
    Route::get('{order}/edit',  [orderController::class, 'edit']);
    Route::put('{order}',  [orderController::class, 'update']);
    Route::get('{order}/cancel',  [orderController::class, 'cancel']);
    Route::get('{order}/statusLogs', [orderController::class, "statusLogs"]);


    Route::get('order_commissions',  [orderController::class, 'order_commissions']);
    Route::get('order_commissions/search',  [orderController::class, 'order_commissions_search']);
});



Route::prefix("moderators")->middleware("isUserOnly")->group(function () {

    Route::get('/', [moderatorsController::class, 'index']);
    Route::get('create', [moderatorsController::class, 'create']);
    Route::post('/', [moderatorsController::class, 'store']);
    Route::get('{id}/edit', [moderatorsController::class, 'edit']);
    Route::put('{id}', [moderatorsController::class, 'update']);
    Route::DELETE('destroy', [moderatorsController::class, 'destroy']);
    Route::get('restore/{id}', [moderatorsController::class, 'restore']);
    Route::get('withdraws', [moderatorsController::class, 'withdraws_index']);
    Route::post('withdraws', [moderatorsController::class, 'withdraws_store']);

    Route::get('search', [moderatorsController::class, 'search']);
});



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


Route::get('/mark-notification-as-read/{id}', function ($id) {

    $notification = auth()->user()->notifications->find($id);


    if ($notification) {
        $notification->markAsRead();
    }

    return response()->json(['message' => 'Notification marked as read']);
});


Route::get('notifications', function () {
    return view("users/notifications");
});


Route::put('notification_settings', function (Request $request) {
    try {
        $user = auth()->user();

        $user->notification_settings =  array_values($request->input("notification"));
        $user->save();



        return Redirect::back()->with("success", "تم التعديل بنجاح");
    } catch (\Throwable $th) {

        return Redirect::back()->with("error", "لم يتم التعديل");
    }
});
