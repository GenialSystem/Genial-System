<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'login']);
Route::get('get_orders_for_user/{id}', [OrderController::class, 'orders']);
Route::middleware('auth:sanctum')->post('logout', [LoginController::class, 'logout']);