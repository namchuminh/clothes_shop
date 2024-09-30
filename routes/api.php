<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ToppingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DetailOrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatisticsController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['jwt.auth'])->group(function () {
    Route::post('/products', [ProductController::class, 'store'])->middleware('role:manager'); 
    Route::post('/products/{product}', [ProductController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware('role:manager');

    Route::post('/products/{product}/detail', [ProductController::class, 'storeDetail'])->middleware('role:manager');
    Route::delete('/products/{detail}/detail', [ProductController::class, 'deleteDetail'])->middleware('role:manager');

    Route::post('/products/{product}/color', [ProductController::class, 'storeColor'])->middleware('role:manager');
    Route::delete('/products/{color}/color', [ProductController::class, 'deleteColor'])->middleware('role:manager');

    Route::post('/products/{product}/topping', [ProductController::class, 'storeTopping'])->middleware('role:manager');
    Route::delete('/products/{topping}/topping', [ProductController::class, 'deleteTopping'])->middleware('role:manager');
    
    Route::post('/colors', [ColorController::class, 'store'])->middleware('role:manager'); 
    Route::post('/colors/{color}', [ColorController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/colors/{color}', [ColorController::class, 'destroy'])->middleware('role:manager'); 

    Route::post('/sizes', [SizeController::class, 'store'])->middleware('role:manager'); 
    Route::post('/sizes/{size}', [SizeController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/sizes/{size}', [SizeController::class, 'destroy'])->middleware('role:manager'); 

    Route::post('/toppings', [ToppingController::class, 'store'])->middleware('role:manager'); 
    Route::post('/toppings/{toppings}', [ToppingController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/toppings/{toppings}', [ToppingController::class, 'destroy'])->middleware('role:manager'); 
    
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('role:manager'); 
    Route::post('/categories/{category}', [CategoryController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('role:manager'); 

    Route::post('/coupon', [CouponController::class, 'store'])->middleware('role:manager'); 
    Route::post('/coupon/{coupon}', [CouponController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/coupon/{coupon}', [CouponController::class, 'destroy'])->middleware('role:manager'); 

    Route::get('/employees', [EmployeeController::class, 'index'])->middleware('role:manager');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->middleware('role:manager');
    Route::post('/employees', [EmployeeController::class, 'store'])->middleware('role:manager'); 
    Route::post('/employees/{employee}', [EmployeeController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->middleware('role:manager'); 

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store'])->middleware('role:customer'); 
    Route::post('/orders/{order}/pay', [OrderController::class, 'pay'])->middleware('role:manager,employee'); 
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']); 
    Route::post('/orders/{order}/status', [OrderController::class, 'status'])->middleware('role:manager,employee'); 

    Route::get('/orders/{order}/detail', [DetailOrderController::class, 'show']);
    Route::post('/orders/{order}/detail', [DetailOrderController::class, 'store'])->middleware('role:customer');

    Route::get('/carts', [CartController::class, 'index'])->middleware('role:customer');
    Route::post('/carts', [CartController::class, 'store'])->middleware('role:customer');
    Route::post('/carts/{cart}', [CartController::class, 'update'])->middleware('role:customer');
    Route::delete('/carts/{cart}', [CartController::class, 'destroy'])->middleware('role:customer');

    Route::get('/user', [UserController::class, 'index'])->middleware('role:manager,employee');
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/user/{user}/block', [UserController::class, 'block']);

    Route::get('/statistics', [StatisticsController::class, 'index'])->middleware('role:manager,employee');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::get('/statistics/revenue', [StatisticsController::class, 'monthlyRevenue']);
Route::get('/statistics/order', [StatisticsController::class, 'monthlyOrder']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/products/{product}/detail', [ProductController::class, 'detail']);
Route::get('/products/{product}/color', [ProductController::class, 'color']);
Route::get('/products/{product}/topping', [ProductController::class, 'topping']);


Route::get('/colors', [ColorController::class, 'index']);
Route::get('/colors/{color}', [ColorController::class, 'show']);

Route::get('/sizes', [SizeController::class, 'index']);
Route::get('/sizes/{size}', [SizeController::class, 'show']);

Route::get('/toppings', [ToppingController::class, 'index']);
Route::get('/toppings/{toppings}', [ToppingController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/coupon', [CouponController::class, 'index']);
Route::get('/coupon/{coupon}', [CouponController::class, 'show']);

Route::get('/user/{user}', [UserController::class, 'show']);