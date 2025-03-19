<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KomplainController;
use App\Http\Controllers\Api\PembayaranController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/checkAuth', [AuthController::class, 'checkAuth'])->middleware('auth:sanctum');
Route::get('validasi-reservasi', [PembayaranController::class, 'valdisiReservasi']);

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('mgmt')->group(function () {
        Route::prefix('pembayaran')->group(function () {
            Route::get('/', [PembayaranController::class, 'index']);
            Route::get('/dataQris', [PembayaranController::class, 'fetchPembayaran']);
        });

        Route::prefix('komplain')->group(function () {
            Route::get('/', [KomplainController::class, 'index']);
            Route::post('/store', [KomplainController::class, 'store']);
        });
    });
});
