<?php

use App\Http\Controllers\Api\Seller\AuthController;
use App\Http\Controllers\Api\Seller\CoponController;
use App\Http\Controllers\Api\Seller\EventController;
use App\Http\Controllers\Api\Seller\ProductController;
use App\Http\Controllers\Api\Seller\ProfileController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// seller
Route::post('seller/register',[AuthController::class,'register']);
Route::post('seller/login',[AuthController::class,'login']);


Route::middleware("isApiSeller")->prefix("seller")->group(function(){
// Product
    Route::get('/product',[ProductController::class,'index']);
    Route::get('/product-show/{id}',[ProductController::class,'show']);
    Route::post('/product-store',[ProductController::class,'store']);
    Route::post('/product-update/{id}',[ProductController::class,'update']);
    Route::get('/product-delete/{id}',[ProductController::class,'delete']);

// Event
    Route::get('/event',[EventController::class,'index']);
    Route::get('/event-show/{id}',[EventController::class,'show']);
    Route::post('/event-store',[EventController::class,'store']);
    Route::post('/event-update/{id}',[EventController::class,'update']);
    Route::get('/event-delete/{id}',[EventController::class,'delete']);


// Copon
    Route::get('/copon',[CoponController::class,'index']);
    Route::get('/copon-show/{id}',[CoponController::class,'show']);
    Route::post('/copon-store',[CoponController::class,'store']);
    Route::post('/copon-update/{id}',[CoponController::class,'update']);
    Route::get('/copon-delete/{id}',[CoponController::class,'delete']);

//profile
    Route::post('/profile-update',[ProfileController::class,'update']);

// logout
    Route::post('/logout',[AuthController::class,'logout']);

});
Route::get('/all_cate',[ProductController::class,'all_cate']);



