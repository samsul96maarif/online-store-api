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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', function(Request $request) {
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => auth()->user()
        ], 200);
    });

    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});

Route::resource('carts', \App\Http\Controllers\CartController::class);
Route::resources([
    'products' => \App\Http\Controllers\ProductController::class,
    'carts' => \App\Http\Controllers\CartController::class,
    'checkouts' => \App\Http\Controllers\InvoiceController::class
]);
