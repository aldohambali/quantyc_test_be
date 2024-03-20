<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->get('/users',[App\Http\Controllers\Api\UsersController::class,'index'])->name('users');

Route::middleware('auth:api')->post('/users',[App\Http\Controllers\Api\UsersController::class,'update'])->name('add users');

Route::middleware('auth:api')->post('/users/delete', [App\Http\Controllers\Api\UsersController::class,'delete'])->name('delete users');


Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');






