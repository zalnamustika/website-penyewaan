<?php

use App\Http\Controllers\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

Route::prefix('v1')->group(function () {
    Route::prefix('/product')->group(function () {
        Route::get('/', [ProductApiController::class, 'showAllProduct']);
        Route::get('/{id}',[ProductApiController::class, 'detail']);
    });

    Route::prefix('/category')->group(function () {
        Route::get('/', [ProductApiController::class, 'showAllCategory']);
    });
});

Route::get('/kalender-produk', function() {
    $order = DB::table('orders')
    ->join('products', 'products.id','=','orders.product_id')
    ->join('payments','payments.id','=','orders.payment_id')
    ->where('orders.status', 2)
    ->where('payments.status', 3)
    ->get(['nama_produk AS title','starts AS start','ends AS end']);
    return json_encode($order);
});

Route::get('/kalender-produk/{id}', function($id) {
    $order = DB::table('orders')
    ->join('products', 'products.id','=','orders.product_id')
    ->join('payments','payments.id','=','orders.payment_id')
    ->where('products.id', $id)
    ->where('orders.status', 2)
    ->where('payments.status', 3)
    ->get(['nama_produk AS title','starts AS start','ends AS end']);

    return json_encode($order);
});

