<?php

use App\Http\Controllers\users\profile\profileController;
use App\Models\page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

include('auth_routes.php');


Route::get('/',function (){

    if (Auth::check()) {
         return redirect("/home");
     }
     else{
        return redirect("/login");
     }

 });


Route::middleware("auth")->get('home', function () {
    if (auth()->user()->role == 'admin') {
        return redirect("admin/home");
    } else if (auth()->user()->role == 'user') {
        return redirect("users/home");
    } else if (auth()->user()->role == 'moderator') {
        return redirect("users/home");
    } else if (auth()->user()->role == 'trader') {
        return redirect("trader/home");
    } else if (auth()->user()->role == 'postman') {
        return redirect("postman/home");
    } else {
        dd(auth()->user()->role);
    }
});



Route::prefix("profile")->middleware("auth")->group(function () {

    Route::get('/', [profileController::class, 'index']);
    Route::put('info', [profileController::class, 'info']);
    Route::put('password', [profileController::class, 'password']);

    Route::post('payment-methods', [profileController::class, 'store']);
});


Route::prefix("pages")->middleware("auth")->group(function () {
    Route::get('{page}', function ($slug) {
        $page = page::where("slug", $slug)->first();
        return view("page", compact("page"));
    });
});




Route::get('markAll',  function () {
    $all = auth()->user()->unreadNotifications;
    if ($all) {
        $all->markAsRead();
        return back()->with('success', "تم تعيين كل الرسائل كمقروء");
    }
});


// Route::get('test_api', function () {
//     testApi();
// });
