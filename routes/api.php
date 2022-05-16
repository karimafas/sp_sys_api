<?php

use App\Http\Controllers\AuthController;
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

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post("/logout", [AuthController::class, "logout"]);

    Route::post("/customers", [CustomerController::class, "getCustomerByPhone"]);
    Route::post("/customers/name", [CustomerController::class, "getCustomerByName"]);
    Route::post("/customers/search", [CustomerController::class, "searchCustomer"]);
    Route::post("/customers/create", [CustomerController::class, "createCustomer"]);
    Route::put("/customers", [CustomerController::class, "updateCustomer"]);
    Route::get("/customers/{id}", [CustomerController::class, "getCustomerByID"]);

    Route::post("/orders", [OrderController::class, "store"]);
    Route::post("/orders/date", [OrderController::class, "getOrdersByDate"]);
    Route::post("/orders/customers", [OrderController::class, "getCustomersForOrders"]);
    Route::post("/orders/hide/{id}", [OrderController::class, "hide"]);
    Route::post("/orders/{id}", [OrderController::class, "update"]);
    Route::post("/orders/rider/{id}", [OrderController::class, "assignRider"]);
    Route::get("/order-number", [OrderController::class, "getOrderNumber"]);
    Route::get("/orders/{id}", [OrderController::class, "index"]);
    Route::get("/best/products", [OrderController::class, "getBestSellerProducts"]);
    Route::get("/best/variations", [OrderController::class, "getBestSellerVariations"]);

    Route::get("/content", [ContentController::class, "getJson"]);

    Route::get("/products/{id}", [ContentController::class, "getProduct"]);
    Route::delete("/products/{id}", [ContentController::class, "deleteProduct"]);
    Route::post("/products", [ContentController::class, "storeProduct"]);
    Route::put("/products/{id}", [ContentController::class, "updateProduct"]);

    Route::get("/variations/{id}", [ContentController::class, "getVariation"]);
    Route::delete("/variations/{id}", [ContentController::class, "deleteVariation"]);
    Route::post("/variations", [ContentController::class, "storeVariation"]);
    Route::put("/variations/{id}", [ContentController::class, "updateVariation"]);
});
