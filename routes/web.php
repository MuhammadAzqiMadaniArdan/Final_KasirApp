<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", [AuthController::class, 'login'])->name('login');
Route::post("/login-auth", [AuthController::class, 'loginAuth'])->name('login.auth');
Route::get("/logout", [AuthController::class, 'logout'])->name('logout');

Route::get("/permission", function () {
    return view('error.permission');
})->name('error.permission');


Route::middleware('IsLogin')->group(function () {

    Route::get("/dashboard", [UserController::class, 'dashboard'])->name('dashboard');

    Route::middleware('IsAdmin')->group(function () {
        Route::prefix('/user')->name('user.')->group(function () {
            Route::get("/create", [UserController::class, 'create'])->name('create');
            Route::post("/store", [UserController::class, 'store'])->name('store');
            Route::get("/edit/{id}", [UserController::class, 'edit'])->name('edit');
            Route::patch("/update/{id}", [UserController::class, 'update'])->name('update');
            Route::delete("/delete/{id}", [UserController::class, 'destroy'])->name('delete');
        });
        Route::prefix('/product')->name('product.')->group(function () {
            Route::get("/create", [ProductController::class, 'create'])->name('create');
            Route::post("/store", [ProductController::class, 'store'])->name('store');
            Route::get("/edit/{id}", [ProductController::class, 'edit'])->name('edit');
            Route::patch("/update/{id}", [ProductController::class, 'update'])->name('update');
            Route::patch("/updateStock/{id}", [ProductController::class, 'updateStock'])->name('updateStock');
            Route::delete("/delete/{id}", [ProductController::class, 'destroy'])->name('delete');
        });
    });
    
    Route::middleware('IsEmployee')->group(function () {
        
        Route::prefix('/order')->name('order.')->group(function () {
            Route::get("/cart", [OrderController::class, 'cart'])->name('cart');
            Route::post("/cartPost", [OrderController::class, 'cartPost'])->name('cart.post');
            Route::get("/create", [OrderController::class, 'create'])->name('create');
            Route::post("/store", [OrderController::class, 'store'])->name('store');
            Route::get("/receipt/{id}", [OrderController::class, 'receipt'])->name('receipt');
            Route::get("/member/{id}", [MemberController::class, 'index'])->name('member');
            Route::post("/member/store/{id}", [MemberController::class, 'store'])->name('member.store');
        });
    });
    
    Route::prefix('/order')->name('order.')->group(function () {
        Route::get("/", [OrderController::class, 'index'])->name('index');
        Route::get("/download-pdf/{id}", [OrderController::class, 'downloadPdf'])->name('download.pdf');
        Route::get("/excel", [OrderController::class, 'exportExcel'])->name('export.excel');
        Route::get("/excel/month", [OrderController::class, 'exportExcelMonthly'])->name('export.excel.month');
        Route::get("/excel/year", [OrderController::class, 'exportExcelYear'])->name('export.excel.year');
    });
    
    Route::prefix('/product')->name('product.')->group(function () {
        Route::get("/", [ProductController::class, 'index'])->name('index');
        Route::get("/excel", [ProductController::class, 'exportProduct'])->name('export.excel');
    });
    
    Route::prefix('/user')->name('user.')->group(function () {
        Route::get("/", [UserController::class, 'index'])->name('index');
        Route::get("/excel", [UserController::class, 'exportUser'])->name('export.excel');
    });

});
