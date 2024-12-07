<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('products', \App\Http\Controllers\ProductController::class);

Route::post('/login', [AuthenticatedSessionController::class, 'store']);


Route::post('/users/register', [UserController::class, 'register']);





Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{cartId}/update', [CartController::class, 'updateQuantity']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
    Route::delete('/cart-clear', [CartController::class, 'clear']);
});


