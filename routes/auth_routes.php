<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;


Route::get('login',function (){

return view('auth/login');


})->name("login");

Route::post('login', [authController::class ,"login"])->name("login");

Route::get('logout', [authController::class, "logout"]);


Route::get('register', function () {
return view('auth/register');
});

Route::post('register', [authController::class ,"register"])->name("register");


Route::get('reset_password', function()
{
  return view("auth/reset_password");
});

Route::post('reset_password', [authController::class, "reset_password"]);
Route::get('restPassword/{token}',[authController::class,"create_password_form"]);
Route::post('create_password', [authController::class, "create_password"]);



// //******************************** auth
