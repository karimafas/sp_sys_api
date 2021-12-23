<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContentController;
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

Route::post("/customers", [CustomerController::class, "getCustomerByPhone"]);
Route::post("/customers/create", [CustomerController::class, "createCustomer"]);
Route::put("/customers", [CustomerController::class, "updateCustomer"]);
Route::get("/customers/{id}", [CustomerController::class, "getCustomerByID"]);

Route::post("/orders", [OrderController::class, "store"]);
Route::post("/orders/date", [OrderController::class, "getOrdersByDate"]);
Route::put("/orders/{id}", [OrderController::class, "update"]);

Route::get("/content", [ContentController::class, "getJson"]);
